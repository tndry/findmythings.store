<?php

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== GOOGLE OAUTH CONFIGURATION DEBUG ===\n\n";

// 1. Check .env configuration
echo "1. ENV Configuration:\n";
echo "APP_URL: " . env('APP_URL') . "\n";
echo "GOOGLE_CLIENT_ID: " . env('GOOGLE_CLIENT_ID') . "\n";
echo "GOOGLE_CLIENT_SECRET: " . (env('GOOGLE_CLIENT_SECRET') ? 'SET' : 'NOT SET') . "\n";
echo "GOOGLE_REDIRECT_URI: " . env('GOOGLE_REDIRECT_URI') . "\n\n";

// 2. Check database configuration
echo "2. Database Social Configuration:\n";
$config = DB::table('settings')->where('space', 'social')->where('name', 'social')->first();
if ($config) {
    echo "Social config exists: YES\n";
    $decoded = json_decode($config->value, true);
    echo "Raw config: " . $config->value . "\n";
    
    if (isset($decoded['google'])) {
        echo "Google provider exists: YES\n";
        echo "Google active: " . ($decoded['google']['active'] ?? 'undefined') . "\n";
        echo "Google client_id: " . ($decoded['google']['client_id'] ?? 'undefined') . "\n";
        echo "Google client_secret: " . (isset($decoded['google']['client_secret']) ? 'SET' : 'NOT SET') . "\n";
        echo "Google redirect_uri: " . ($decoded['google']['redirect_uri'] ?? 'undefined') . "\n";
    } else {
        echo "Google provider exists: NO\n";
    }
} else {
    echo "Social config exists: NO\n";
}

// 3. Check route existence
echo "\n3. Route Configuration:\n";
try {
    $routes = Route::getRoutes();
    $googleRedirect = false;
    $googleCallback = false;
    
    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'auth/google')) {
            echo "Google redirect route: " . $route->uri() . " -> " . $route->getActionName() . "\n";
            $googleRedirect = true;
        }
        if (str_contains($route->uri(), 'social/google/callback')) {
            echo "Google callback route: " . $route->uri() . " -> " . $route->getActionName() . "\n";
            $googleCallback = true;
        }
    }
    
    if (!$googleRedirect) echo "Google redirect route: NOT FOUND\n";
    if (!$googleCallback) echo "Google callback route: NOT FOUND\n";
    
} catch (Exception $e) {
    echo "Error checking routes: " . $e->getMessage() . "\n";
}

// 4. Check if SocialRepo can initialize
echo "\n4. SocialRepo Test:\n";
try {
    $socialRepo = \InnoShop\Common\Repositories\Customer\SocialRepo::getInstance();
    $socialRepo->initSocialConfig();
    echo "SocialRepo initialization: SUCCESS\n";
    
    // Test Socialite driver
    $driver = \Laravel\Socialite\Facades\Socialite::driver('google');
    echo "Socialite Google driver: SUCCESS\n";
    
} catch (Exception $e) {
    echo "SocialRepo/Socialite error: " . $e->getMessage() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
