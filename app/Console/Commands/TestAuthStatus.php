<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestAuthStatus extends Command
{
    protected $signature = 'test:auth-status';
    protected $description = 'Test current authentication status';

    public function handle()
    {
        $this->info('=== Authentication Status Test ===');
        
        // Test default auth guard (web)
        if (auth()->check()) {
            $user = auth()->user();
            $this->info('✓ Web Guard - User logged in');
            $this->info('  - ID: ' . $user->id);
            $this->info('  - Email: ' . $user->email);
            $this->info('  - Email verified: ' . ($user->hasVerifiedEmail() ? 'Yes' : 'No'));
        } else {
            $this->error('✗ Web Guard - No user logged in');
        }
        
        // Test customer guard
        if (auth('customer')->check()) {
            $customer = auth('customer')->user();
            $this->info('✓ Customer Guard - Customer logged in');
            $this->info('  - ID: ' . $customer->id);
            $this->info('  - Email: ' . $customer->email);
            $this->info('  - Email verified: ' . (method_exists($customer, 'hasVerifiedEmail') ? ($customer->hasVerifiedEmail() ? 'Yes' : 'No') : 'N/A'));
        } else {
            $this->error('✗ Customer Guard - No customer logged in');
        }
        
        $this->info('');
        $this->info('=== Middleware Requirements ===');
        $this->info('For /titip-jual route you need:');
        $this->info('1. Customer guard authentication (customer_auth:customer)');
        $this->info('2. Email verification (verified)');
        $this->info('3. Front middleware (front)');
        
        $this->info('');
        $this->info('=== Test Complete ===');
    }
}
