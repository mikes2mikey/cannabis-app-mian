<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayFastService
{
    protected $merchantId;
    protected $merchantKey;
    protected $passphrase;
    public $testMode;
    protected $processUrl;
    protected $validateUrl;
    protected $validHosts;

    /**
     * Constructor - setup PayFast configuration
     */
    public function __construct()
    {
        $this->merchantId = env('PAYFAST_MERCHANT_ID', '10037908'); // Default to test
        $this->merchantKey = env('PAYFAST_MERCHANT_KEY', 'tx5mubb77h7fd'); // Default to test
        $this->passphrase = env('PAYFAST_PASSPHRASE', 'Snoopdogg12_1506'); // Default test passphrase
        $this->testMode = env('PAYFAST_TEST_MODE', true);

        // Set URLs based on mode
        if ($this->testMode) {
            $this->processUrl = env('PAYFAST_SANDBOX_URL', 'https://sandbox.payfast.co.za/eng/process');
            $this->validateUrl = env('PAYFAST_SANDBOX_VERIFY_URL', 'https://sandbox.payfast.co.za/eng/query/validate');
        } else {
            $this->processUrl = env('PAYFAST_LIVE_PROCESS_URL', 'https://www.payfast.co.za/eng/process');
            $this->validateUrl = env('PAYFAST_LIVE_VERIFY_URL', 'https://www.payfast.co.za/eng/query/validate');
        }
        
        // Default valid hosts for PayFast's servers
        $this->validHosts = [
            'www.payfast.co.za',
            'sandbox.payfast.co.za',
            'w1w.payfast.co.za',
            'w2w.payfast.co.za',
        ];
    }

    /**
     * Generate payment data for a PayFast transaction
     *
     * @param object $order Order with amount, description, id
     * @param object $user User with first_name, last_name, email
     * @param array|null $recurring Optional recurring billing parameters
     * @return array Payment data for form submission
     */
    public function generatePaymentData($order, $user, $recurring = null)
    {
        // Format the amount correctly with 2 decimal places
        $amount = number_format($order->amount, 2, '.', '');
        
        // Create initial payment data array
        $data = [
            // Merchant details
            'merchant_id' => $this->merchantId,
            'merchant_key' => $this->merchantKey,
            
            // URLs
            'return_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
            'notify_url' => route('payment.notify'),
            
            // Customer details
            'name_first' => $user->first_name,
            'name_last' => $user->last_name,
            'email_address' => $user->email,
            
            // Transaction details
            'm_payment_id' => $order->id,
            'amount' => $amount,
            'item_name' => $order->description,
        ];
        
        // Add recurring billing data if provided
        if ($recurring !== null) {
            $data['subscription_type'] = $recurring['subscription_type'];
            $data['billing_date'] = $recurring['billing_date'];
            $data['recurring_amount'] = number_format($recurring['recurring_amount'], 2, '.', '');
            $data['frequency'] = $recurring['frequency'];
            $data['cycles'] = $recurring['cycles'];
        }
        
        // Add test mode flag if in test mode
        if ($this->testMode) {
            $data['test_mode'] = '1';
        }
        
        // Generate signature
        $data['signature'] = $this->generateSignature($data);
        
        return $data;
    }

    /**
     * Generate a payment URL for direct submission via GET
     *
     * @param object $order Order data
     * @param object $user User data
     * @param array|null $recurring Recurring billing data
     * @return string Payment URL with query parameters
     */
    public function generatePaymentUrl($order, $user, $recurring = null)
    {
        // Get the payment data
        $paymentData = $this->generatePaymentData($order, $user, $recurring);
        
        // Create the query string
        $queryString = http_build_query($paymentData);
        
        // Return the full URL
        return $this->processUrl . '?' . $queryString;
    }

    /**
     * Generate a signature for the payment data
     *
     * @param array $data Payment data
     * @return string MD5 hash signature
     */
    public function generateSignature(array $data)
    {
        // Create parameter string
        $pfOutput = '';
        foreach ($data as $key => $val) {
            if ($val !== '' && $key !== 'signature') {
                // Use rawurlencode and replace %20 with + to match JS implementation
                $encodedValue = str_replace('%20', '+', rawurlencode(trim($val)));
                $pfOutput .= $key .'='. $encodedValue .'&';
            }
        }
        
        // Remove last ampersand
        $getString = substr($pfOutput, 0, -1);
        
        // Add passphrase if set - also with consistent encoding
        if ($this->passphrase !== null && $this->passphrase !== '') {
            $encodedPassphrase = str_replace('%20', '+', rawurlencode(trim($this->passphrase)));
            $getString .= '&passphrase='. $encodedPassphrase;
        }
        
        // Log the signature string for debugging
        Log::debug('PayFast signature string: ' . $getString);
        
        return md5($getString);
    }
    
    /**
     * Verify payment notification from PayFast
     *
     * @param Request $request The notification request
     * @return bool Whether the payment is valid
     */
    public function verifyPayment(Request $request)
    {
        Log::info('Verifying PayFast payment', $request->all());
        
        // Check if it's a valid host
        if (!$this->isValidHost()) {
            Log::error('Invalid PayFast host');
            return false;
        }
        
        // Verify signature
        if (!$this->verifySignature($request->all())) {
            Log::error('Invalid PayFast signature');
            return false;
        }
        
        // Check payment status
        if ($request->input('payment_status') !== 'COMPLETE') {
            Log::error('Payment not complete', ['status' => $request->input('payment_status')]);
            return false;
        }
        
        // Validate with PayFast server
        if (!$this->validateWithPayFast($request->all())) {
            Log::error('PayFast server validation failed');
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if the request is coming from a valid PayFast host
     *
     * @return bool
     */
    protected function isValidHost()
    {
        $serverIp = $_SERVER['REMOTE_ADDR'] ?? null;
        
        // Allow localhost in test mode
        if ($this->testMode && in_array($serverIp, ['127.0.0.1', '::1'])) {
            return true;
        }
        
        // Otherwise, check if it's a valid PayFast server
        return in_array($serverIp, $this->validHosts);
    }
    
    /**
     * Verify the signature of the data
     *
     * @param array $data Payment data
     * @return bool Whether the signature is valid
     */
    protected function verifySignature(array $data)
    {
        $receivedSignature = $data['signature'] ?? null;
        if (!$receivedSignature) {
            return false;
        }
        
        // Remove signature from data for recalculation
        $dataToVerify = $data;
        unset($dataToVerify['signature']);
        
        // Generate our own signature
        $calculatedSignature = $this->generateSignature($dataToVerify);
        
        // Log for debugging
        Log::debug('PayFast signature verification', [
            'received' => $receivedSignature,
            'calculated' => $calculatedSignature,
            'match' => ($calculatedSignature === $receivedSignature)
        ]);
        
        // Compare signatures
        return $calculatedSignature === $receivedSignature;
    }
    
    /**
     * Validate the payment with PayFast's server
     *
     * @param array $data Payment data
     * @return bool Whether the payment is valid
     */
    protected function validateWithPayFast(array $data)
    {
        // In test mode, we can skip validation for local development
        if ($this->testMode && in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'])) {
            return true;
        }
        
        // Send validation request to PayFast
        $response = Http::post($this->validateUrl, $data);
        
        // Check if validation succeeded
        return $response->successful() && $response->body() === 'VALID';
    }
} 