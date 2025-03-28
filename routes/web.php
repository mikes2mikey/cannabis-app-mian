<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\GrowthLogController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PlantImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\PublicRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/qr-code', [ProfileController::class, 'generateQrCode'])->name('profile.generate-qr');
    
    // QR code verification route
    Route::post('/verify-qr-code', [QrCodeController::class, 'verify'])->name('qrcode.verify');

    // Plant routes
    Route::resource('plants', PlantController::class);

    // Growth log routes
    Route::post('/plants/{plant}/growth-logs', [GrowthLogController::class, 'store'])->name('growth-logs.store');
    Route::get('/growth-logs/{growthLog}/edit', [GrowthLogController::class, 'edit'])->name('growth-logs.edit');
    Route::put('/growth-logs/{growthLog}', [GrowthLogController::class, 'update'])->name('growth-logs.update');
    Route::delete('/growth-logs/{growthLog}', [GrowthLogController::class, 'destroy'])->name('growth-logs.destroy');

    // Plant image routes
    Route::post('/plants/{plant}/images', [PlantImageController::class, 'store'])->name('plant-images.store');
    Route::delete('/plant-images/{image}', [PlantImageController::class, 'destroy'])->name('plant-images.destroy');
    
    // Admin routes
    Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('memberships', MembershipController::class);
    });
});

// Public registration routes
Route::get('/register/public', [PublicRegistrationController::class, 'showRegistrationForm'])->name('register.public.show');
Route::post('/register/public', [PublicRegistrationController::class, 'register'])->name('register.public');

// PayFast payment callback routes
Route::get('/payment/success', [PublicRegistrationController::class, 'paymentCallback'])->name('payment.success');
Route::get('/payment/cancel', function() {
    return redirect()->route('register.public.show')->with('error', 'Payment was cancelled. Please try again.');
})->name('payment.cancel');
Route::post('/payment/notify', [PublicRegistrationController::class, 'paymentCallback'])->name('payment.notify');

require __DIR__.'/auth.php';

// Temporary test route to verify PayFast signature generation
Route::get('/test-payfast-signature', function (\App\Services\PayFastService $payFastService) {
    $order = (object) [
        'amount' => 99.99,
        'description' => 'Basic Membership',
        'id' => 1,
    ];

    $user = (object) [
        'first_name' => 'michael',
        'last_name' => 'menzies',
        'email' => 'mikesmenz1833@gmail.com',
    ];

    $paymentData = $payFastService->generatePaymentData($order, $user);
    
    // Show what gets sent to PayFast
    return response()->json([
        'payment_data' => $paymentData,
        'endpoint' => config('payfast.test_mode') 
            ? config('payfast.endpoints.sandbox.process') 
            : config('payfast.endpoints.live.process')
    ]);
});

// Detailed PayFast debugging route
Route::get('/debug-payfast', function (\App\Services\PayFastService $payFastService) {
    // Basic transaction data
    $order = (object) [
        'amount' => 99.99,
        'description' => 'Basic Membership',
        'id' => 1,
    ];

    $user = (object) [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
    ];

    // Generate payment data
    $paymentData = $payFastService->generatePaymentData($order, $user);
    
    // Get configuration
    $config = [
        'merchant_id' => config('payfast.merchant_id'),
        'merchant_key' => config('payfast.merchant_key'),
        'test_mode' => config('payfast.test_mode'),
        'passphrase' => config('payfast.passphrase') ? 'Set (not shown)' : 'Not set',
        'sandbox_process_url' => config('payfast.endpoints.sandbox.process'),
        'live_process_url' => config('payfast.endpoints.live.process'),
        'active_url' => config('payfast.test_mode') 
            ? config('payfast.endpoints.sandbox.process') 
            : config('payfast.endpoints.live.process'),
        'env_variables' => [
            'PAYFAST_MERCHANT_ID' => env('PAYFAST_MERCHANT_ID'),
            'PAYFAST_MERCHANT_KEY' => env('PAYFAST_MERCHANT_KEY'),
            'PAYFAST_TEST_MODE' => env('PAYFAST_TEST_MODE'),
        ]
    ];
    
    // Return form HTML for direct testing
    $formHtml = '<form action="' . $config['active_url'] . '" method="POST" id="payfast-form">';
    foreach ($paymentData as $key => $value) {
        $formHtml .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
    }
    $formHtml .= '<button type="submit">Submit to PayFast</button>';
    $formHtml .= '</form>';
    
    return response()->json([
        'config' => $config,
        'payment_data' => $paymentData,
        'form_html' => $formHtml
    ]);
});

// PayFast test form
Route::get('/test-form', function (\App\Services\PayFastService $payFastService) {
    $order = (object) [
        'amount' => 99.99,
        'description' => 'Basic Membership',
        'id' => 1,
    ];

    $user = (object) [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
    ];

    // Generate payment data
    $paymentData = $payFastService->generatePaymentData($order, $user);
    
    // Get the active URL
    $activeUrl = config('payfast.test_mode') 
        ? config('payfast.endpoints.sandbox.process') 
        : config('payfast.endpoints.live.process');
    
    // Build the HTML
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>PayFast Test Form</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">PayFast Test Form</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">This form will submit a test payment to PayFast sandbox using the standard test credentials.</p>
                            
                            <div class="mb-4 p-3 bg-light rounded">
                                <strong>Important:</strong> For testing credit card payments, use:
                                <ul class="mb-0 mt-2">
                                    <li>Card Number: 5200 0000 0000 0015</li>
                                    <li>Any future expiry date</li>
                                    <li>Any 3-digit CVV</li>
                                </ul>
                            </div>
                            
                            <form action="' . $activeUrl . '" method="POST" id="payfast-form">';
    
    // Add all fields from payment data
    foreach ($paymentData as $key => $value) {
        $html .= '
                                <div class="mb-3">
                                    <label class="form-label">' . $key . '</label>
                                    <input type="text" name="' . $key . '" value="' . $value . '" class="form-control" readonly>
                                </div>';
    }
    
    $html .= '
                                <button type="submit" class="btn btn-success btn-lg w-100">Submit Payment to PayFast</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';
    
    return response($html);
});

// PayFast signature debug with recurring billing
Route::get('/debug-signature', function (\App\Services\PayFastService $payFastService) {
    // Use the official test credentials with recurring billing fields
    $testData = [
        'merchant_id' => '10000100',
        'merchant_key' => '46f0cd694581a',
        'return_url' => 'https://www.example.com/return',
        'cancel_url' => 'https://www.example.com/cancel',
        'notify_url' => 'https://www.example.com/notify',
        'name_first' => 'Test',
        'name_last' => 'User',
        'email_address' => 'test@example.com',
        'cell_number' => '0823456789',
        'm_payment_id' => '1',
        'amount' => '100.00',
        'item_name' => 'Test Item',
        'item_description' => 'A test product',
        'email_confirmation' => '1',
        'confirmation_address' => 'test@example.com',
        
        // Recurring billing fields (required)
        'subscription_type' => '1',        // 1 for subscription
        'billing_date' => date('Y-m-d'),   // Start date for billing
        'recurring_amount' => '100.00',    // Amount to bill in future
        'frequency' => '3',                // 3 for monthly
        'cycles' => '12',                  // Number of cycles (12 months)
    ];
    
    // Add test mode if enabled
    if (config('payfast.test_mode')) {
        $testData['test_mode'] = '1';
    }
    
    // Generate our signature
    $testData['signature'] = $payFastService->generateSignature($testData);
    
    // Create signature debug info
    $debugInfo = [
        'data_array' => $testData,
        'query_string' => http_build_query($testData),
        'signature_components' => [],
        'note' => 'Use this exact data on PayFast sandbox to test signature generation with recurring billing',
    ];
    
    // Build URL for easy testing
    $payfastUrl = config('payfast.test_mode')
        ? config('payfast.endpoints.sandbox.process')
        : config('payfast.endpoints.live.process');
    
    $fullUrl = $payfastUrl . '?' . http_build_query($testData);
    
    // Generate simple test form
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>PayFast Signature Debug - Recurring Billing</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">PayFast Recurring Billing Test</h4>
                        </div>
                        <div class="card-body">
                            <p>This form has a pre-generated signature using standardized test data with <strong>recurring billing fields</strong>.</p>
                            
                            <div class="alert alert-info">
                                <p><strong>Generated Signature:</strong> ' . $testData['signature'] . '</p>
                                <hr>
                                <p class="mb-0"><strong>Passphrase used:</strong> ' . (config('payfast.passphrase') ? 'Yes (from config)' : 'No') . '</p>
                            </div>
                            
                            <form action="' . $payfastUrl . '" method="POST" id="payfast-form">';
    
    // Add all fields from test data
    foreach ($testData as $key => $value) {
        $html .= '
                                <div class="mb-3">
                                    <label class="form-label">' . $key . '</label>
                                    <input type="text" name="' . $key . '" value="' . $value . '" class="form-control" readonly>
                                </div>';
    }
    
    $html .= '
                                <button type="submit" class="btn btn-success btn-lg w-100">Submit To PayFast</button>
                            </form>
                            
                            <div class="mt-4">
                                <h5>Recurring Billing Fields:</h5>
                                <ul>
                                    <li><strong>subscription_type:</strong> 1 (Subscription)</li>
                                    <li><strong>billing_date:</strong> Today\'s date (start date)</li>
                                    <li><strong>recurring_amount:</strong> R100.00</li>
                                    <li><strong>frequency:</strong> 3 (Monthly)</li>
                                    <li><strong>cycles:</strong> 12 (12 months)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';
    
    return response($html);
});

// PayFast recurring billing test form
Route::get('/test-recurring', function (\App\Services\PayFastService $payFastService) {
    $order = (object) [
        'amount' => 99.99,
        'description' => 'Monthly Membership',
        'id' => 1,
    ];

    $user = (object) [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
    ];

    // Recurring billing parameters
    $recurringParams = [
        'subscription_type' => 1,          // 1 for subscription
        'billing_date' => date('Y-m-d'),   // Today as start date
        'recurring_amount' => 99.99,       // Same as initial amount
        'frequency' => 3,                  // 3 for monthly
        'cycles' => 12                     // 12 months subscription
    ];

    // Generate payment data with recurring parameters
    $paymentData = $payFastService->generatePaymentData($order, $user, $recurringParams);
    
    // Get the active URL
    $activeUrl = config('payfast.test_mode') 
        ? config('payfast.endpoints.sandbox.process') 
        : config('payfast.endpoints.live.process');
    
    // Build the HTML
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>PayFast Recurring Billing Test</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">PayFast Recurring Billing Test</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">This form will submit a test <strong>recurring payment</strong> to PayFast sandbox using the standard test credentials.</p>
                            
                            <div class="mb-4 p-3 bg-light rounded">
                                <strong>Important:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>For testing card payments: 5200 0000 0000 0015</li>
                                    <li>This will set up a MONTHLY recurring payment of R99.99 for 12 months</li>
                                    <li>A passphrase is being used for the signature: ' . (config('payfast.passphrase') ? 'Yes' : 'No') . '</li>
                                </ul>
                            </div>
                            
                            <form action="' . $activeUrl . '" method="POST" id="payfast-form">';
    
    // Add all fields from payment data
    foreach ($paymentData as $key => $value) {
        $html .= '
                                <div class="mb-3">
                                    <label class="form-label">' . $key . '</label>
                                    <input type="text" name="' . $key . '" value="' . $value . '" class="form-control" readonly>
                                </div>';
    }
    
    $html .= '
                                <button type="submit" class="btn btn-success btn-lg w-100">Submit Recurring Payment to PayFast</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';
    
    return response($html);
});

// PayFast test URL generator
Route::get('/test-url', function () {
    // Construct variables exactly as shown in the documentation
    $cartTotal = 99.99;
    $passphrase = 'jt7NOE43FZPn'; // Official test passphrase
    
    // Create the data array in exact order needed for signature calculation
    $data = [
        // Merchant details
        'merchant_id' => '10000100',
        'merchant_key' => '46f0cd694581a',
        'return_url' => url('/payment/success'),
        'cancel_url' => url('/payment/cancel'),
        'notify_url' => url('/payment/notify'),
        
        // Buyer details
        'name_first' => 'Test',
        'name_last' => 'User',
        'email_address' => 'test@example.com',
        
        // Transaction details
        'm_payment_id' => '1234', // Unique payment ID
        'amount' => number_format(sprintf('%.2f', $cartTotal), 2, '.', ''),
        'item_name' => 'Basic Membership',
        
        // Subscription details
        'subscription_type' => '1',
        'billing_date' => date('Y-m-d'),
        'recurring_amount' => number_format(sprintf('%.2f', $cartTotal), 2, '.', ''),
        'frequency' => '3', // monthly
        'cycles' => '12'
    ];
    
    // Generate signature exactly as in PayFast example
    function generateSignature($data, $passPhrase = null) {
        // Create parameter string
        $pfOutput = '';
        foreach ($data as $key => $val) {
            if ($val !== '') {
                $pfOutput .= $key .'='. urlencode(trim($val)) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr($pfOutput, 0, -1);
        if ($passPhrase !== null) {
            $getString .= '&passphrase='. urlencode(trim($passPhrase));
        }
        return md5($getString);
    }
    
    // Generate the signature
    $signature = generateSignature($data, $passphrase);
    $data['signature'] = $signature;
    
    // Generate URL
    $baseUrl = 'https://sandbox.payfast.co.za/eng/process';
    $queryString = http_build_query($data);
    $fullUrl = $baseUrl . '?' . $queryString;
    
    // Display both the URL and a clickable link
    return response()->json([
        'url' => $fullUrl,
        'query_params' => $data,
        'html' => '<a href="'.$fullUrl.'" target="_blank">Click here to test PayFast recurring payment</a>'
    ]);
});

// PayFast URL test with service
Route::get('/payment-url', function (App\Services\PayFastService $payFastService) {
    // Create order object
    $order = (object) [
        'amount' => 99.99,
        'description' => 'Basic Membership',
        'id' => rand(1000, 9999),  // Random ID for testing
    ];

    // Create user object
    $user = (object) [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
    ];

    // Create recurring parameters
    $recurring = [
        'subscription_type' => 1,
        'billing_date' => date('Y-m-d'),
        'recurring_amount' => 99.99,
        'frequency' => 3,  // Monthly
        'cycles' => 12     // 12 months
    ];

    // Generate payment URL
    $paymentUrl = $payFastService->generatePaymentUrl($order, $user, $recurring);
    
    // Get the payment data
    $paymentData = $payFastService->generatePaymentData($order, $user, $recurring);
    
    // Build a test HTML page
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>PayFast Implementation Test</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Add PayFast metadata for JavaScript implementation -->
        <meta name="payfast-merchant-id" content="'.$paymentData['merchant_id'].'">
        <meta name="payfast-merchant-key" content="'.$paymentData['merchant_key'].'">
        <meta name="payfast-return-url" content="'.$paymentData['return_url'].'">
        <meta name="payfast-cancel-url" content="'.$paymentData['cancel_url'].'">
        <meta name="payfast-notify-url" content="'.$paymentData['notify_url'].'">
        <meta name="payfast-amount" content="'.$paymentData['amount'].'">
        <meta name="payfast-item-name" content="'.$paymentData['item_name'].'">
        <meta name="payfast-payment-id" content="'.$paymentData['m_payment_id'].'">
        <meta name="payfast-test-mode" content="'.($payFastService->testMode ? 'true' : 'false').'">
    </head>
    <body>
        <div class="container py-5">
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0">PayFast Implementation Test</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                This page demonstrates different ways to implement PayFast payment forms using both server-side (PHP) and client-side (JavaScript) approaches.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">1. Direct URL Approach</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">The simplest implementation: generate a URL with all parameters and redirect the user.</p>
                            <a href="'.$paymentUrl.'" class="btn btn-success btn-lg w-100">Click Here to Test Direct URL</a>
                            
                            <div class="mt-3">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#urlDetails">
                                    View URL Details
                                </button>
                                <div class="collapse mt-2" id="urlDetails">
                                    <div class="card card-body bg-light">
                                        <small class="text-muted word-break">'.$paymentUrl.'</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">2. PHP Form Submission</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Using PHP to generate a form with hidden fields (server-side approach).</p>
                            
                            <form action="'.($payFastService->testMode ? 'https://sandbox.payfast.co.za/eng/process' : 'https://www.payfast.co.za/eng/process').'" method="POST">';
    
    // Add all fields from payment data
    foreach ($paymentData as $key => $val) {
        $html .= '
                                <input type="hidden" name="'.$key.'" value="'.$val.'">';
    }
    
    $html .= '
                                <button type="submit" class="btn btn-primary btn-lg w-100">Submit PHP Form</button>
                            </form>
                            
                            <div class="mt-3">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#phpFormDetails">
                                    View Form Data
                                </button>
                                <div class="collapse mt-2" id="phpFormDetails">
                                    <div class="card card-body bg-light">
                                        <pre class="mb-0"><code>';
    
    // Format payment data for display
    foreach ($paymentData as $key => $val) {
        $html .= htmlspecialchars($key) . ': ' . htmlspecialchars($val) . "\n";
    }
    
    $html .= '</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0">3. JavaScript Form Generator</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Using JavaScript to dynamically generate the form based on metadata.</p>
                            <div id="js-form-container"></div>
                            
                            <div class="mt-3">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#jsDetails">
                                    View JavaScript Code
                                </button>
                                <div class="collapse mt-2" id="jsDetails">
                                    <div class="card card-body bg-light">
<pre><code>// Dynamically generate the form
document.addEventListener("DOMContentLoaded", function() {
  insertPayFastForm("js-form-container");
});</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h4 class="mb-0">4. Vanilla JavaScript Example</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Using vanilla JavaScript array notation as per the example code.</p>
                            <div id="vanilla-js-form-container"></div>
                            
                            <div class="mt-3">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#vanillaDetails">
                                    View Example Code
                                </button>
                                <div class="collapse mt-2" id="vanillaDetails">
                                    <div class="card card-body bg-light">
<pre><code>const myData = [];
// Merchant details
myData["merchant_id"] = "10000100";
myData["merchant_key"] = "46f0cd694581a";
myData["return_url"] = "http://www.yourdomain.co.za/return_url";
myData["cancel_url"] = "http://www.yourdomain.co.za/cancel_url";
myData["notify_url"] = "http://www.yourdomain.co.za/notify_url";
// Buyer details
myData["name_first"] = "First Name";
myData["name_last"] = "Last Name";
myData["email_address"] = "test@test.com";
// Transaction details
myData["m_payment_id"] = "1234";
myData["amount"] = "10.00";
myData["item_name"] = "Order#123";

// Generate signature
const myPassphrase = "jt7NOE43FZPn";
myData["signature"] = generateSignature(myData, myPassphrase);

let htmlForm = `<form action="https://\${pfHost}/eng/process" method="post">`;
for (let key in myData) {
  if(myData.hasOwnProperty(key)){
    value = myData[key];
    if (value !== "") {
      htmlForm +=`<input name="\${key}" type="hidden" value="\${value.trim()}" />`;
    }
  }
}

htmlForm += \'<input type="submit" value="Pay Now" /></form>\';</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bootstrap and JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/js/payfast-browser.js"></script>
        <script src="/js/payfast-form.js"></script>
        <script>
            // Initialize JavaScript form generators when DOM is ready
            document.addEventListener("DOMContentLoaded", function() {
                // Method 3: JavaScript form generator
                insertPayFastForm("js-form-container");
                
                // Method 4: Vanilla JS implementation
                if (window.createDemoForm) {
                    document.getElementById("vanilla-js-form-container").innerHTML = createDemoForm();
                }
            });
        </script>
        <style>
            .word-break { word-break: break-all; }
            pre { margin-bottom: 0; }
            code { white-space: pre-wrap; }
        </style>
    </body>
    </html>';
    
    return response($html);
});

// PayFast onsite payment test
Route::get('/test-onsite', function (App\Services\PayFastService $payFastService) {
    // Create order object
    $order = (object) [
        'amount' => 99.99,
        'description' => 'Basic Membership',
        'id' => rand(1000, 9999), // Random ID for testing
    ];

    // Create user object
    $user = (object) [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
    ];

    // Create recurring parameters
    $recurring = [
        'subscription_type' => 1,
        'billing_date' => date('Y-m-d'),
        'recurring_amount' => 99.99,
        'frequency' => 3, // Monthly
        'cycles' => 12     // 12 months
    ];

    // Generate payment data
    $paymentData = $payFastService->generatePaymentData($order, $user, $recurring);
    
    // Build a test HTML page for onsite payment
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>PayFast Onsite Payment Test</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="' . csrf_token() . '">
    </head>
    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">PayFast Onsite Payment Test</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">This demonstrates the PayFast onsite payment solution where users never leave your website.</p>
                            
                            <div class="alert alert-info mb-4">
                                <strong>Test Credit Card:</strong> 5200 0000 0000 0015<br>
                                <strong>Expiry Date:</strong> Any future date<br>
                                <strong>CVV:</strong> Any 3-digit number
                            </div>
                            
                            <div id="payment-status" class="mb-4" style="display: none;"></div>
                            
                            <button type="button" id="payfast-pay-button" class="btn btn-success btn-lg w-100">
                                Pay R' . $paymentData['amount'] . ' Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- PayFast Onsite Engine Script -->
        <script src="https://www.payfast.co.za/onsite/engine.js"></script>
        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const payButton = document.getElementById("payfast-pay-button");
                const statusEl = document.getElementById("payment-status");
                
                // Function to show status message
                function showStatus(message, type = "info") {
                    statusEl.className = "alert alert-" + type + " mb-4";
                    statusEl.innerHTML = message;
                    statusEl.style.display = "block";
                }
                
                // Process payment when button is clicked
                payButton.addEventListener("click", function() {
                    // Show processing state
                    payButton.disabled = true;
                    payButton.innerHTML = \'<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...\';
                    
                    // Test if PayFast script is loaded
                    if (typeof window.payfast_do_onsite_payment !== "function") {
                        showStatus("PayFast onsite script failed to load. Please refresh the page.", "danger");
                        payButton.disabled = false;
                        payButton.innerHTML = "Try Again";
                        return;
                    }
                    
                    // Define payment data
                    const paymentData = ' . json_encode($paymentData) . ';
                    
                    try {
                        // Call PayFast onsite payment
                        window.payfast_do_onsite_payment(paymentData, function(result) {
                            console.log("PayFast result:", result);
                            
                            if (result === true) {
                                // Payment successful
                                showStatus("Payment successful! Thank you.", "success");
                                payButton.style.display = "none";
                            } else {
                                // Payment failed
                                let errorMessage = "Payment failed or was cancelled.";
                                if (typeof result === "string") {
                                    errorMessage = result;
                                }
                                showStatus("Payment error: " + errorMessage, "danger");
                                
                                // Reset button
                                payButton.disabled = false;
                                payButton.innerHTML = "Try Again";
                            }
                        });
                    } catch (error) {
                        // Handle errors
                        console.error("Error initiating payment:", error);
                        showStatus("Error initiating payment: " + error.message, "danger");
                        payButton.disabled = false;
                        payButton.innerHTML = "Try Again";
                    }
                });
            });
        </script>
    </body>
    </html>';
    
    return response($html);
});
