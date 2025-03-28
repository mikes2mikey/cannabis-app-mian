<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\MembershipPlan;
use App\Services\PayFastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class PublicRegistrationController extends Controller
{
    protected $payFastService;

    public function __construct(PayFastService $payFastService)
    {
        $this->payFastService = $payFastService;
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        $membershipPlans = MembershipPlan::where('is_active', true)->get();
        
        // Debug: Log the membership plans count
        Log::info('Fetched membership plans for registration page', [
            'count' => $membershipPlans->count(),
            'plans' => $membershipPlans->toArray()
        ]);
        
        // If no active membership plans, create a default one for display
        if ($membershipPlans->isEmpty()) {
            Log::warning('No active membership plans found. Creating a temporary default plan for display.');
            $membershipPlans = collect([
                new MembershipPlan([
                    'id' => 1,
                    'name' => 'Default Membership',
                    'description' => 'Basic membership plan with standard features.',
                    'price' => 99.99,
                    'duration_days' => 30,
                    'is_active' => true
                ])
            ]);
        }
        
        return view('auth.register-public', compact('membershipPlans'));
    }

    /**
     * Process the registration form submission
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'max:20'],
            'id_number' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
        ]);

        // Get the selected membership plan
        $membershipPlan = MembershipPlan::findOrFail($request->membership_plan_id);

        // Store the registration data in the session for later use
        $request->session()->put('registration_data', $request->all());
        
        // Generate PayFast payment form data
        $paymentData = $this->generatePaymentData($request, $membershipPlan);
        
        return view('auth.payment', compact('paymentData', 'membershipPlan'));
    }

    /**
     * Handle PayFast callback after payment
     */
    public function paymentCallback(Request $request)
    {
        Log::info('PayFast callback received', $request->all());

        // Verify the payment 
        if (!$this->payFastService->verifyPayment($request)) {
            Log::error('PayFast callback verification failed');
            return redirect()->route('register.public.show')
                ->with('error', 'Payment verification failed. Please try again or contact support.');
        }

        // Get registration data from session
        $registrationData = session('registration_data');
        if (!$registrationData) {
            Log::error('No registration data found in session');
            return redirect()->route('register.public.show')
                ->with('error', 'Registration data not found. Please try again.');
        }

        // Process the successful registration
        try {
            DB::beginTransaction();

            // Create the user
            $user = User::create([
                'name' => $registrationData['name'],
                'email' => $registrationData['email'],
                'password' => Hash::make($registrationData['password']),
                'role' => 'member',
                'membership_plan_id' => $registrationData['membership_plan_id'],
                'email_verified_at' => null, // User needs to verify email separately
            ]);

            // Create user profile
            UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $registrationData['first_name'],
                'last_name' => $registrationData['last_name'],
                'phone_number' => $registrationData['phone_number'],
                'id_number' => $registrationData['id_number'],
                'date_of_birth' => $registrationData['date_of_birth'],
                'address' => $registrationData['address'],
                'city' => $registrationData['city'],
                'postal_code' => $registrationData['postal_code'],
            ]);

            DB::commit();

            // Clear the registration data from session
            session()->forget('registration_data');

            // Log the user in
            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('dashboard')
                ->with('success', 'Registration successful! Welcome to the Cannabis Membership Portal.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed after payment: ' . $e->getMessage());
            
            return redirect()->route('register.public.show')
                ->with('error', 'There was an error processing your registration. Please try again or contact support.');
        }
    }

    /**
     * Generate the PayFast payment data array
     */
    private function generatePaymentData(Request $request, MembershipPlan $membershipPlan)
    {
        // Create a simple order object to pass to the service
        $order = (object) [
            'amount' => $membershipPlan->price,
            'description' => $membershipPlan->name,
            'id' => $membershipPlan->id,
        ];

        // Create a user object with the request data
        $user = (object) [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ];

        // Check if this plan should use recurring billing
        $recurring = null;
        if ($membershipPlan->is_recurring) {
            // For monthly plans (Basic), use frequency=3
            // For annual plans, use frequency=6
            $frequency = $membershipPlan->duration_days >= 360 ? 6 : 3;
            
            $recurring = [
                'subscription_type' => 1,                  // 1 for subscription
                'billing_date' => date('Y-m-d'),           // Start today
                'recurring_amount' => $membershipPlan->price,  // Same amount for recurring payments
                'frequency' => $frequency,                 // 3=monthly, 4=quarterly, 5=biannually, 6=annually
                'cycles' => $membershipPlan->calculateDurationMonths() > 12 ? 12 : $membershipPlan->calculateDurationMonths()  // Max 12 cycles, per PayFast recommendation
            ];
            
            Log::info('Setting up recurring billing for membership', [
                'plan' => $membershipPlan->name,
                'frequency' => $frequency,
                'cycles' => $recurring['cycles'],
                'recurring_amount' => $recurring['recurring_amount']
            ]);
        }

        // Generate the payment data
        $paymentData = $this->payFastService->generatePaymentData($order, $user, $recurring);
        
        // Add form action URL
        $paymentData['form_action'] = $this->payFastService->testMode 
            ? env('PAYFAST_SANDBOX_URL', 'https://sandbox.payfast.co.za/eng/process')
            : env('PAYFAST_LIVE_PROCESS_URL', 'https://www.payfast.co.za/eng/process');
            
        return $paymentData;
    }
} 