<?php

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING GOOGLE OAUTH DATABASE FORMAT ===\n\n";

// Format yang benar sesuai dengan SocialRepo expectations
$correctGoogleConfig = [
    [
        'provider' => 'google',
        'active' => 1,
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect_uri' => env('GOOGLE_REDIRECT_URI')
    ]
];

// Convert ke JSON string
$configJson = json_encode($correctGoogleConfig);

echo "1. Correct configuration format:\n";
echo "Config JSON: " . $configJson . "\n\n";

// Update konfigurasi dengan format yang benar
echo "2. Updating social config to correct format...\n";
DB::table('settings')
    ->where('space', 'social')
    ->where('name', 'social')
    ->update([
        'value' => $configJson,
        'updated_at' => now()
    ]);

echo "3. Verifying configuration...\n";
$check = DB::table('settings')->where('space', 'social')->where('name', 'social')->first();
if ($check) {
    echo "Configuration updated successfully!\n";
    echo "Stored value: " . $check->value . "\n";
    
    $decoded = json_decode($check->value, true);
    if (is_array($decoded) && isset($decoded[0]['provider']) && $decoded[0]['provider'] === 'google') {
        echo "Google OAuth format is now CORRECT!\n";
        echo "Provider: " . $decoded[0]['provider'] . "\n";
        echo "Active: " . $decoded[0]['active'] . "\n";
        echo "Client ID: " . $decoded[0]['client_id'] . "\n";
    } else {
        echo "WARNING: Google OAuth configuration format issue!\n";
    }
} else {
    echo "ERROR: Failed to find configuration!\n";
}

// Test SocialRepo initialization
echo "\n4. Testing SocialRepo...\n";
try {
    $socialRepo = \InnoShop\Common\Repositories\Customer\SocialRepo::getInstance();
    $socialRepo->initSocialConfig();
    echo "SocialRepo initialization: SUCCESS!\n";
    
    // Test Socialite driver
    $driver = \Laravel\Socialite\Facades\Socialite::driver('google');
    echo "Socialite Google driver: SUCCESS!\n";
    
} catch (Exception $e) {
    echo "SocialRepo/Socialite error: " . $e->getMessage() . "\n";
}

echo "\n=== FORMAT FIX COMPLETE ===\n";
