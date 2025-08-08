<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use InnoShop\Common\Repositories\Customer\SocialRepo;

class TestGoogleOAuth extends Command
{
    protected $signature = 'test:google-oauth';
    protected $description = 'Test Google OAuth configuration';

    public function handle()
    {
        $this->info('=== Google OAuth Debug Test ===');
        
        // Test 1: Check if settings exist
        $this->info('1. Checking Google OAuth settings...');
        try {
            $socialSettingsRaw = system_setting('social', []);
            
            // Handle both array and JSON string formats
            if (is_string($socialSettingsRaw)) {
                $socialSettings = json_decode($socialSettingsRaw, true) ?: [];
            } else {
                $socialSettings = $socialSettingsRaw;
            }
            
            if (empty($socialSettings)) {
                $this->error('   ERROR: No social settings found!');
            } else {
                $this->info('   Found ' . count($socialSettings) . ' social provider(s)');
                foreach ($socialSettings as $setting) {
                    $this->info('   Provider: ' . ($setting['provider'] ?? 'N/A'));
                    $this->info('   Active: ' . (($setting['active'] ?? false) ? 'Yes' : 'No'));
                    $this->info('   Has client_id: ' . (isset($setting['client_id']) ? 'Yes' : 'No'));
                    $this->info('   Has client_secret: ' . (isset($setting['client_secret']) ? 'Yes' : 'No'));
                }
            }
        } catch (\Exception $e) {
            $this->error('   ERROR: ' . $e->getMessage());
        }

        // Test 2: Try to initialize social config
        $this->info('');
        $this->info('2. Testing SocialRepo initSocialConfig...');
        try {
            SocialRepo::getInstance()->initSocialConfig();
            $this->info('   SUCCESS: Social config initialized');
        } catch (\Exception $e) {
            $this->error('   ERROR: ' . $e->getMessage());
        }

        // Test 3: Check if Google is configured in services
        $this->info('');
        $this->info('3. Checking services configuration...');
        $googleConfig = config('services.google');
        if (empty($googleConfig)) {
            $this->error('   ERROR: No Google configuration found in services');
        } else {
            $this->info('   Google client_id: ' . (isset($googleConfig['client_id']) ? 'Set' : 'Not set'));
            $this->info('   Google client_secret: ' . (isset($googleConfig['client_secret']) ? 'Set' : 'Not set'));
            $this->info('   Google redirect: ' . ($googleConfig['redirect'] ?? 'Not set'));
        }

        // Test 4: Test route existence
        $this->info('');
        $this->info('4. Testing Google OAuth routes...');
        try {
            $redirectRoute = route('google.redirect');
            $callbackRoute = route('social.callback');
            $this->info('   Redirect route: ' . $redirectRoute);
            $this->info('   Callback route: ' . $callbackRoute);
        } catch (\Exception $e) {
            $this->error('   ERROR: ' . $e->getMessage());
        }

        $this->info('');
        $this->info('=== Test Complete ===');
    }
}
