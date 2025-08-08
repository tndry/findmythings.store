<?php

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== GOOGLE OAUTH LOG MONITOR ===\n\n";

// Clear previous logs
\Log::info('=== NEW GOOGLE OAUTH TEST SESSION STARTED ===', [
    'timestamp' => now(),
    'test_mode' => true
]);

echo "✅ Log monitor started. Logs akan tersimpan di Laravel log.\n";
echo "✅ GoogleLoginController sudah diupdate dengan extensive logging.\n\n";

echo "🎯 INSTRUCTIONS UNTUK TESTING:\n";
echo "1. Buka browser: https://findmythings.store/id/register\n";
echo "2. Klik tombol 'Masuk dengan Akun IPB'\n";
echo "3. Login dengan email IPB (@apps.ipb.ac.id)\n";
echo "4. Setelah proses selesai, jalankan script berikut untuk cek log:\n\n";

echo "COMMAND UNTUK CEK LOG:\n";
echo "ssh tandry@findmythings.store \"cd /var/www/findmythings && tail -50 storage/logs/laravel.log | grep 'Google OAuth'\"\n\n";

echo "Atau untuk monitoring real-time:\n";
echo "ssh tandry@findmythings.store \"cd /var/www/findmythings && tail -f storage/logs/laravel.log | grep 'Google OAuth'\"\n\n";

echo "=== MONITORING READY ===\n";
