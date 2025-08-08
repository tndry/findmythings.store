<?php

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SETTING UP GOOGLE OAUTH CONFIGURATION ===\n\n";

// Konfigurasi Google OAuth yang sesuai dengan .env production
$googleConfig = [
    'google' => [
        'active' => 1,
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect_uri' => env('GOOGLE_REDIRECT_URI')
    ]
];

// Convert ke JSON string
$configJson = json_encode($googleConfig);

echo "1. Setting configuration:\n";
echo "Config JSON: " . $configJson . "\n\n";

// Cek apakah ada konfigurasi sebelumnya
$existing = DB::table('settings')->where('space', 'social')->where('name', 'social')->first();

if ($existing) {
    echo "2. Updating existing social config...\n";
    DB::table('settings')
        ->where('space', 'social')
        ->where('name', 'social')
        ->update([
            'value' => $configJson,
            'updated_at' => now()
        ]);
} else {
    echo "2. Creating new social config...\n";
    DB::table('settings')->insert([
        'space' => 'social',
        'name' => 'social',
        'value' => $configJson,
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

echo "3. Verifying configuration...\n";
$check = DB::table('settings')->where('space', 'social')->where('name', 'social')->first();
if ($check) {
    echo "Configuration saved successfully!\n";
    echo "Stored value: " . $check->value . "\n";
    
    $decoded = json_decode($check->value, true);
    if (isset($decoded['google']) && $decoded['google']['active'] == 1) {
        echo "Google OAuth is now ACTIVE!\n";
    } else {
        echo "WARNING: Google OAuth configuration issue!\n";
    }
} else {
    echo "ERROR: Failed to save configuration!\n";
}

echo "\n=== SETUP COMPLETE ===\n";
