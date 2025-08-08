<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing UploadService on server...\n";

// Create a simple test image (1x1 pixel PNG)
$testImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');

$tempFile = tempnam(sys_get_temp_dir(), 'test_upload_');
file_put_contents($tempFile, $testImageData);

$uploadedFile = new \Illuminate\Http\UploadedFile(
    $tempFile,
    'test_server_fix.png',
    'image/png',
    null,
    true
);

$uploadService = new \InnoShop\RestAPI\Services\UploadService();
$result = $uploadService->uploadForPanel($uploadedFile, 'products');

echo "Upload result on server:\n";
echo "URL: " . $result['url'] . "\n";
echo "Value: " . $result['value'] . "\n";

// Check expected vs actual
$expectedUrlPattern = 'https://findmythings.store/storage/catalog/products/';
if (strpos($result['url'], $expectedUrlPattern) === 0) {
    echo "✅ URL format is correct!\n";
} else {
    echo "❌ URL format is wrong. Expected to start with: $expectedUrlPattern\n";
}

// Cleanup
unlink($tempFile);
