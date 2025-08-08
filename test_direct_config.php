<?php

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIRECT SOCIALITE CONFIG TEST ===\n\n";

// Bypass system_setting dan langsung set config untuk test
echo "1. Setting Socialite config directly...\n";

// Set Google Socialite config langsung
\Illuminate\Support\Facades\Config::set('services.google', [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI')
]);

echo "Google config set directly to services.google\n";
echo "Client ID: " . config('services.google.client_id') . "\n";
echo "Redirect: " . config('services.google.redirect') . "\n\n";

// Test Socialite driver
echo "2. Testing Socialite Google driver...\n";
try {
    $driver = \Laravel\Socialite\Facades\Socialite::driver('google');
    echo "Socialite Google driver: SUCCESS!\n";
    
    // Test redirect URL generation
    $redirectUrl = $driver->with(['hd' => 'apps.ipb.ac.id'])->getTargetUrl();
    echo "Generated redirect URL works: YES\n";
    echo "Redirect URL preview: " . substr($redirectUrl, 0, 100) . "...\n";
    
} catch (Exception $e) {
    echo "Socialite error: " . $e->getMessage() . "\n";
}

echo "\n3. Testing routes...\n";
try {
    // Test route generation
    $authRoute = route('auth.google');
    echo "Auth route: " . $authRoute . "\n";
    
    $callbackRoute = route('social.google.callback');
    echo "Callback route: " . $callbackRoute . "\n";
    
} catch (Exception $e) {
    echo "Route error: " . $e->getMessage() . "\n";
}

echo "\n=== DIRECT CONFIG TEST COMPLETE ===\n";
