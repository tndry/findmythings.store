<?php
// COMPREHENSIVE GOOGLE LOGIN DEBUG

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== COMPREHENSIVE GOOGLE LOGIN DEBUG ===\n\n";

// 1. Check database setting
echo "1. DATABASE SOCIAL SETTINGS:\n";
$socialRaw = system_setting('social');
echo "Raw value: " . var_export($socialRaw, true) . "\n";
echo "Type: " . gettype($socialRaw) . "\n";

if (is_string($socialRaw)) {
    $social = json_decode($socialRaw, true);
    echo "Decoded: " . var_export($social, true) . "\n";
} else {
    $social = $socialRaw;
}

if ($social) {
    $activeCount = collect($social)->where('active', true)->count();
    echo "Active providers: $activeCount\n";
    foreach ($social as $provider) {
        echo "- {$provider['provider']}: " . ($provider['active'] ? 'ACTIVE' : 'INACTIVE') . "\n";
    }
} else {
    echo "NO SOCIAL SETTINGS FOUND!\n";
}

// 2. Check template condition
echo "\n2. TEMPLATE CONDITION CHECK:\n";
$conditionResult = collect(system_setting('social'))->where('active', true)->count();
echo "Template condition result: $conditionResult\n";
echo "Should show Google login: " . ($conditionResult > 0 ? 'YES' : 'NO') . "\n";

// 3. Check file existence
echo "\n3. FILE EXISTENCE:\n";
$socialFile = __DIR__ . '/innopacks/front/resources/views/account/_social.blade.php';
echo "Social file exists: " . (file_exists($socialFile) ? 'YES' : 'NO') . "\n";
if (file_exists($socialFile)) {
    echo "File size: " . filesize($socialFile) . " bytes\n";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($socialFile)) . "\n";
}

// 4. Check login template includes
echo "\n4. LOGIN TEMPLATE CHECK:\n";
$loginFile = __DIR__ . '/innopacks/front/resources/views/account/login.blade.php';
if (file_exists($loginFile)) {
    $loginContent = file_get_contents($loginFile);
    $hasSocialInclude = strpos($loginContent, '@include(\'account/_social\')') !== false;
    echo "Login template includes _social: " . ($hasSocialInclude ? 'YES' : 'NO') . "\n";
    
    if (!$hasSocialInclude) {
        echo "PROBLEM: Login template does not include _social!\n";
        echo "Looking for other social includes...\n";
        if (strpos($loginContent, '_social') !== false) {
            echo "Found '_social' reference in login template\n";
        } else {
            echo "NO social reference found in login template!\n";
        }
    }
} else {
    echo "Login template not found!\n";
}

// 5. Check routes
echo "\n5. ROUTE CHECK:\n";
try {
    $googleRoute = url('/auth/google');
    echo "Google route URL: $googleRoute\n";
    
    // Test route accessibility
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 10,
            'ignore_errors' => true
        ]
    ]);
    
    $headers = get_headers($googleRoute, 1, $context);
    if ($headers) {
        echo "Route response: " . $headers[0] . "\n";
    } else {
        echo "Route not accessible\n";
    }
} catch (Exception $e) {
    echo "Route error: " . $e->getMessage() . "\n";
}

// 6. Check view cache
echo "\n6. VIEW CACHE CHECK:\n";
$viewCacheDir = __DIR__ . '/storage/framework/views';
if (is_dir($viewCacheDir)) {
    $cacheFiles = glob($viewCacheDir . '/*');
    echo "View cache files: " . count($cacheFiles) . "\n";
    
    // Look for social-related cache
    foreach ($cacheFiles as $file) {
        if (strpos(file_get_contents($file), 'google-auth-section') !== false) {
            echo "Found compiled social template: " . basename($file) . "\n";
            break;
        }
    }
} else {
    echo "View cache directory not found\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
