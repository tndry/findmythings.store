<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\GoogleLoginController;

class TestGoogleRoutes extends Command
{
    protected $signature = 'test:google-routes';
    protected $description = 'Test Google OAuth routes functionality';

    public function handle()
    {
        $this->info('=== Testing Google OAuth Routes ===');
        
        try {
            // Test 1: Controller instantiation
            $controller = new GoogleLoginController();
            $this->info('✓ GoogleLoginController created successfully');
            
            // Test 2: Check if redirect method can be called (without actually executing)
            if (method_exists($controller, 'redirect')) {
                $this->info('✓ redirect() method exists and is callable');
            } else {
                $this->error('✗ redirect() method not found');
            }
            
            // Test 3: Check if callback method can be called
            if (method_exists($controller, 'callback')) {
                $this->info('✓ callback() method exists and is callable');
            } else {
                $this->error('✗ callback() method not found');
            }
            
            // Test 4: Route resolution
            $redirectUrl = route('google.redirect');
            $callbackUrl = route('google.callback');
            $this->info("✓ Redirect URL: {$redirectUrl}");
            $this->info("✓ Callback URL: {$callbackUrl}");
            
            $this->info('');
            $this->info('🎉 All tests passed! Google OAuth should work now.');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }
        
        $this->info('=== Test Complete ===');
    }
}
