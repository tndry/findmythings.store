<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create a simple test image (1x1 pixel PNG)
$testImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');

$tempFile = tempnam(sys_get_temp_dir(), 'test_upload_');
file_put_contents($tempFile, $testImageData);

$uploadedFile = new \Illuminate\Http\UploadedFile(
    $tempFile,
    'test_image.png',
    'image/png',
    null,
    true
);

echo "Testing UploadService->uploadForPanel()...\n";

$uploadService = new \InnoShop\RestAPI\Services\UploadService();
$result = $uploadService->uploadForPanel($uploadedFile, 'products');

echo "Upload result:\n";
echo "URL: " . $result['url'] . "\n";
echo "Value: " . $result['value'] . "\n";

// Check if file exists
if (isset($result['value'])) {
    $filePath = storage_path('app/public/catalog/' . $result['value']);
    echo "File path: " . $filePath . "\n";
    echo "File exists: " . (file_exists($filePath) ? 'YES' : 'NO') . "\n";
}

// Cleanup
unlink($tempFile);
