<?php
// Test the fixed condition

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING FIXED CONDITION ===\n";

// Test the new logic
$socialSettings = system_setting('social');
if (is_string($socialSettings)) {
    $socialSettings = json_decode($socialSettings, true) ?: [];
}
$hasActiveProviders = collect($socialSettings)->where('active', true)->count() > 0;

echo "Social settings: " . var_export($socialSettings, true) . "\n";
echo "Has active providers: " . ($hasActiveProviders ? 'YES' : 'NO') . "\n";
echo "Google login should show: " . ($hasActiveProviders ? 'YES' : 'NO') . "\n";

echo "\n=== FIX VERIFICATION COMPLETE ===\n";
