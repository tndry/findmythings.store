<?php
// Test Google OAuth configuration
echo "=== Google OAuth Debug Test ===\n";

// Test 1: Check if settings exist
echo "1. Checking Google OAuth settings...\n";
$socialSettings = system_setting('social', []);
if (empty($socialSettings)) {
    echo "   ERROR: No social settings found!\n";
} else {
    echo "   Found " . count($socialSettings) . " social provider(s)\n";
    foreach ($socialSettings as $setting) {
        echo "   Provider: " . ($setting['provider'] ?? 'N/A') . "\n";
        echo "   Active: " . (($setting['active'] ?? false) ? 'Yes' : 'No') . "\n";
        echo "   Has client_id: " . (isset($setting['client_id']) ? 'Yes' : 'No') . "\n";
        echo "   Has client_secret: " . (isset($setting['client_secret']) ? 'Yes' : 'No') . "\n";
    }
}

// Test 2: Try to initialize social config
echo "\n2. Testing SocialRepo initSocialConfig...\n";
try {
    InnoShop\Common\Repositories\Customer\SocialRepo::getInstance()->initSocialConfig();
    echo "   SUCCESS: Social config initialized\n";
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

// Test 3: Check if Google is configured in services
echo "\n3. Checking services configuration...\n";
$googleConfig = config('services.google');
if (empty($googleConfig)) {
    echo "   ERROR: No Google configuration found in services\n";
} else {
    echo "   Google client_id: " . (isset($googleConfig['client_id']) ? 'Set' : 'Not set') . "\n";
    echo "   Google client_secret: " . (isset($googleConfig['client_secret']) ? 'Set' : 'Not set') . "\n";
    echo "   Google redirect: " . ($googleConfig['redirect'] ?? 'Not set') . "\n";
}

echo "\n=== Test Complete ===\n";
