<?php

namespace App\Console\Commands;

use App\Services\PayFastService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestPayFastIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payfast:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PayFast integration in sandbox mode';

    /**
     * The PayFast service.
     *
     * @var \App\Services\PayFastService
     */
    protected $payFastService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PayFastService $payFastService)
    {
        parent::__construct();
        $this->payFastService = $payFastService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testing PayFast Integration in Sandbox Mode');
        $this->info('-------------------------------------------');
        
        // Check configuration
        $this->info('Checking PayFast configuration...');
        $merchantId = config('payfast.merchant_id');
        $merchantKey = config('payfast.merchant_key');
        $processUrl = config('payfast.endpoints.process');
        $testMode = config('payfast.test_mode');
        
        if (empty($merchantId) || empty($merchantKey)) {
            $this->error('PayFast merchant ID or key is missing in configuration.');
            return 1;
        }
        
        $this->info('Merchant ID: ' . $merchantId);
        $this->info('Process URL: ' . $processUrl);
        $this->info('Test Mode: ' . ($testMode ? 'Enabled' : 'Disabled'));
        
        // Create a test order and user
        $this->info('Creating test payment data...');
        $order = (object) [
            'amount' => 99.99,
            'description' => 'Test Membership',
            'id' => '12345',
        ];
        
        $user = (object) [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ];
        
        try {
            // Generate payment data
            $paymentData = $this->payFastService->generatePaymentData($order, $user);
            
            // Display payment data
            $this->info('Generated PayFast payment data:');
            $this->table(
                ['Key', 'Value'], 
                collect($paymentData)->map(function ($value, $key) {
                    return [$key, is_array($value) ? json_encode($value) : $value];
                })->toArray()
            );
            
            // Generate a test HTML form
            $this->info('Generating test HTML form...');
            $formHtml = '<form action="' . $processUrl . '" method="POST">';
            foreach ($paymentData as $key => $value) {
                $formHtml .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
            }
            $formHtml .= '<button type="submit">Pay Now</button></form>';
            
            // Write the form to a file
            $filePath = public_path('payfast_test.html');
            file_put_contents($filePath, '<!DOCTYPE html><html><head><title>PayFast Test</title></head><body><h1>PayFast Test Payment</h1>' . $formHtml . '</body></html>');
            
            $this->info('Test form generated at: ' . $filePath);
            $this->info('Open this file in a browser to test the PayFast integration.');
            
            $this->info('PayFast integration test completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error testing PayFast integration: ' . $e->getMessage());
            Log::error('PayFast test error: ' . $e->getMessage(), ['exception' => $e]);
            return 1;
        }
        
        return 0;
    }
} 