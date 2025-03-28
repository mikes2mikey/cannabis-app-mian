<x-guest-layout>
    <x-payfast-listener />
    
    <div class="text-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
            <span class="block">Complete Your Payment</span>
        </h1>
        <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
            You're almost there! Please review your selected membership and complete the payment to finalize your registration.
        </p>
    </div>

    <div class="space-y-8">
        <!-- Payment Summary -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">
                    Payment Summary
                </h2>
                <p class="mt-1 text-indigo-100">
                    Review your selected membership plan details
                </p>
            </div>

            <div class="p-6">
                <div class="bg-indigo-50 rounded-lg p-6 border border-indigo-100 mb-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-xl font-bold text-indigo-900">{{ $membershipPlan->name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 mt-2 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $membershipPlan->duration_days }} Days Access
                            </span>
                            @if($membershipPlan->is_recurring)
                                <span class="inline-flex items-center px-2.5 py-0.5 mt-2 ml-2 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Recurring Subscription
                                </span>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Total Due</div>
                            <div class="text-3xl font-bold text-indigo-600">R{{ number_format($membershipPlan->price, 2) }}</div>
                            @if($membershipPlan->is_recurring)
                                <div class="text-xs text-gray-500 mt-1">
                                    Billed {{ $membershipPlan->calculateDurationMonths() > 1 ? 'every '.$membershipPlan->calculateDurationMonths().' months' : 'monthly' }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-indigo-200 pt-4 mt-4">
                        <h4 class="font-medium text-indigo-800 mb-2">Membership Benefits:</h4>
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li class="flex items-center">
                                <svg class="h-4 w-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Full access to all cannabis tracking features
                            </li>
                            <li class="flex items-center">
                                <svg class="h-4 w-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Track multiple plants and growth logs
                            </li>
                            <li class="flex items-center">
                                <svg class="h-4 w-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Priority support from our team
                            </li>
                        </ul>
                        
                        <div class="mt-4 text-sm text-gray-600">
                            {{ $membershipPlan->description }}
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="border rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Payment Method
                    </h3>
                    
                    <div class="space-y-4">
                        <p class="text-gray-600">You'll be redirected to PayFast to complete your payment securely.</p>
                        
                        <div class="flex justify-center py-3 border-t border-b border-gray-100">
                            <img src="https://www.payfast.co.za/assets/images/logos-and-banners/PayFast-Logo.png" alt="PayFast" class="h-12">
                        </div>
                        
                        <div class="bg-yellow-50 rounded-md p-4 border-l-4 border-yellow-400">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Your account will only be created after successful payment.
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($membershipPlan->is_recurring)
                        <div class="bg-blue-50 rounded-md p-4 border-l-4 border-blue-400 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 font-bold mb-1">
                                        Recurring Subscription
                                    </p>
                                    <p class="text-sm text-blue-700 mb-1">
                                        Your card will be billed R{{ number_format($membershipPlan->price, 2) }} 
                                        {{ $membershipPlan->duration_days >= 360 ? 'annually' : ($membershipPlan->calculateDurationMonths() > 1 ? 'every '.$membershipPlan->calculateDurationMonths().' months' : 'monthly') }} 
                                        for a total of {{ $membershipPlan->calculateDurationMonths() > 12 ? 12 : $membershipPlan->calculateDurationMonths() }} billing cycles.
                                    </p>
                                    @if(env('PAYFAST_TEST_MODE', true))
                                    <p class="text-xs text-blue-600 mt-2">
                                        <strong>Testing:</strong> Use card number 5200 0000 0000 0015 with any future expiry date and any 3-digit CVV.
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- PayFast Onsite Payment Container -->
                        <div id="payfast-payment-container">
                            <div class="flex flex-col md:flex-row justify-between mt-6 gap-4">
                                <a href="{{ route('register.public') }}" class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    {{ __('Back to Registration') }}
                                </a>

                                <button type="button" id="payfast-pay-button" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Pay Securely Now') }}
                                    <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Legacy PayFast Form Container (hidden but kept for fallback) -->
                        <div id="payfast-form-container" style="display: none;">
                            <form action="{{ $paymentData['form_action'] ?? env('PAYFAST_SANDBOX_URL', 'https://sandbox.payfast.co.za/eng/process') }}" method="POST" id="payment-form">
                                @foreach ($paymentData as $key => $value)
                                    @if ($key !== 'form_action')
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach

                                <div class="flex flex-col md:flex-row justify-between mt-6 gap-4">
                                    <a href="{{ route('register.public') }}" class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        {{ __('Back to Registration') }}
                                    </a>

                                    <button type="submit" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Proceed to Pay') }}
                                        <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Alternative JavaScript Form Implementation (hidden) -->
                        <div id="js-payfast-form-container" style="display: none;"></div>

                        <!-- PayFast Metadata for JavaScript Implementation -->
                        <meta name="payfast-merchant-id" content="{{ $paymentData['merchant_id'] }}">
                        <meta name="payfast-merchant-key" content="{{ $paymentData['merchant_key'] }}">
                        <meta name="payfast-return-url" content="{{ route('payment.success') }}">
                        <meta name="payfast-cancel-url" content="{{ route('payment.cancel') }}">
                        <meta name="payfast-notify-url" content="{{ route('payment.notify') }}">
                        <meta name="payfast-amount" content="{{ $paymentData['amount'] }}">
                        <meta name="payfast-item-name" content="{{ $paymentData['item_name'] }}">
                        <meta name="payfast-payment-id" content="{{ $paymentData['m_payment_id'] }}">
                        <meta name="payfast-test-mode" content="{{ env('PAYFAST_TEST_MODE', true) ? 'true' : 'false' }}">
                        <meta name="payfast-button-text" content="Pay Securely Now">
                        
                        <!-- Status Message Display -->
                        <div id="payment-status" class="mt-4" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secure Payment Message -->
        <div class="flex items-center justify-center text-sm text-gray-500">
            <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Your payment is processed securely through PayFast
        </div>
    </div>

    @push('scripts')
    <!-- PayFast Onsite Engine Script -->
    <script src="https://www.payfast.co.za/onsite/engine.js" defer></script>
    
    <!-- Include PayFast Implementation Scripts -->
    <script src="{{ asset('js/payfast-browser.js') }}"></script>
    <script src="{{ asset('js/payfast-form.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set up the payment button
            const payButton = document.getElementById('payfast-pay-button');
            
            if (payButton) {
                payButton.addEventListener('click', function() {
                    // Show loading state
                    payButton.disabled = true;
                    payButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
                    
                    // Execute PayFast onsite payment
                    initiateOnsitePayment();
                });
            }
            
            // Function to initiate the onsite payment
            function initiateOnsitePayment() {
                // Check if window.payfast_do_onsite_payment exists
                if (typeof window.payfast_do_onsite_payment !== 'function') {
                    showError('PayFast onsite script failed to load. Redirecting to standard payment...');
                    setTimeout(() => {
                        document.getElementById('payment-form').submit();
                    }, 2000);
                    return;
                }
                
                // Get payment data from meta tags
                const paymentData = {
                    merchant_id: document.querySelector('meta[name="payfast-merchant-id"]').content,
                    merchant_key: document.querySelector('meta[name="payfast-merchant-key"]').content,
                    return_url: document.querySelector('meta[name="payfast-return-url"]').content,
                    cancel_url: document.querySelector('meta[name="payfast-cancel-url"]').content,
                    notify_url: document.querySelector('meta[name="payfast-notify-url"]').content,
                    amount: document.querySelector('meta[name="payfast-amount"]').content,
                    item_name: document.querySelector('meta[name="payfast-item-name"]').content,
                    name_first: '{{ $paymentData["name_first"] }}',
                    name_last: '{{ $paymentData["name_last"] }}',
                    email_address: '{{ $paymentData["email_address"] }}',
                    m_payment_id: document.querySelector('meta[name="payfast-payment-id"]').content,
                    
                    // Recurring billing parameters
                    subscription_type: '{{ $paymentData["subscription_type"] ?? "" }}',
                    billing_date: '{{ $paymentData["billing_date"] ?? "" }}',
                    recurring_amount: '{{ $paymentData["recurring_amount"] ?? "" }}',
                    frequency: '{{ $paymentData["frequency"] ?? "" }}',
                    cycles: '{{ $paymentData["cycles"] ?? "" }}'
                };
                
                // Calculate signature (use server-side signature from the form)
                const signature = '{{ $paymentData["signature"] }}';
                paymentData.signature = signature;
                
                // Debug log
                console.log('Starting PayFast onsite payment with data:', paymentData);
                
                try {
                    // Initiate PayFast onsite payment
                    window.payfast_do_onsite_payment(paymentData, function(result) {
                        console.log('PayFast onsite payment result:', result);
                        
                        if (result === true) {
                            // Payment was successful
                            showSuccess('Payment successful! Redirecting to confirmation page...');
                            // Redirect to success page
                            window.location.href = paymentData.return_url;
                        } else {
                            // Payment failed or was cancelled
                            let errorMessage = 'Payment failed or was cancelled.';
                            if (typeof result === 'string') {
                                errorMessage = result;
                            }
                            showError('Payment error: ' + errorMessage);
                            
                            // Reset payment button
                            const payButton = document.getElementById('payfast-pay-button');
                            if (payButton) {
                                payButton.disabled = false;
                                payButton.innerHTML = '{{ __("Pay Securely Now") }} <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>';
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error initiating PayFast onsite payment:', error);
                    showError('An error occurred while initiating payment. Falling back to redirect payment...');
                    
                    // Fallback to standard form submission
                    setTimeout(() => {
                        document.getElementById('payment-form').submit();
                    }, 2000);
                }
            }
            
            // Helper function to show success message
            function showSuccess(message) {
                const statusElement = document.getElementById('payment-status');
                statusElement.className = 'mt-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700';
                statusElement.innerHTML = '<p class="font-medium">Success!</p><p>' + message + '</p>';
                statusElement.style.display = 'block';
            }
            
            // Helper function to show error message
            function showError(message) {
                const statusElement = document.getElementById('payment-status');
                statusElement.className = 'mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700';
                statusElement.innerHTML = '<p class="font-medium">Error</p><p>' + message + '</p>';
                statusElement.style.display = 'block';
            }
            
            // Setup event listener for Livewire subscription update
            const refreshComponent = () => {
                console.log('Refreshing subscription status');
                if (typeof Livewire !== 'undefined') {
                    Livewire.emit('billingUpdated');
                }
            }
        });
    </script>
    @endpush
</x-guest-layout> 