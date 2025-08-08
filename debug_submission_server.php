<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUGGING SUBMISSION FLOW ON SERVER ===\n\n";

// 1. Cek submission terbaru yang baru di-approve
$submissions = \App\Models\Submission::where('status', 'approved')
    ->orderBy('updated_at', 'desc')
    ->take(3)
    ->get();

echo "Recent approved submissions:\n";
foreach ($submissions as $submission) {
    echo "- Submission ID: {$submission->id}\n";
    echo "  Status: {$submission->status}\n";
    echo "  Updated: {$submission->updated_at}\n";
    echo "  Images: " . $submission->images . "\n\n";
}

// 2. Cek produk yang dibuat dari submission tersebut
echo "Products created from those submissions:\n";
foreach ($submissions as $submission) {
    $product = \InnoShop\Common\Models\Product::where('submission_id', $submission->id)->first();
    if ($product) {
        echo "- Product ID: {$product->id} (from submission {$submission->id})\n";
        echo "  Images: " . (is_string($product->images) ? $product->images : json_encode($product->images)) . "\n";
        
        // Test accessor
        $imageUrls = $product->image_url;
        echo "  Image URLs (accessor): " . $imageUrls . "\n";
        
        // Test manual parsing
        $imagesData = is_string($product->images) ? json_decode($product->images, true) : $product->images;
        if (is_array($imagesData) && !empty($imagesData)) {
            echo "  First image data: " . json_encode($imagesData[0]) . "\n";
            if (isset($imagesData[0]['url'])) {
                echo "  First image URL: " . $imagesData[0]['url'] . "\n";
            }
        }
        echo "\n";
    }
}

// 3. Test UploadService secara manual
echo "=== TESTING UPLOAD SERVICE ===\n";
$testImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
$tempFile = tempnam(sys_get_temp_dir(), 'debug_upload_');
file_put_contents($tempFile, $testImageData);

$uploadedFile = new \Illuminate\Http\UploadedFile(
    $tempFile,
    'debug_test.png',
    'image/png',
    null,
    true
);

$uploadService = new \InnoShop\RestAPI\Services\UploadService();
$result = $uploadService->uploadForPanel($uploadedFile, 'products');

echo "UploadService result:\n";
echo "URL: " . $result['url'] . "\n";
echo "Value: " . $result['value'] . "\n";

unlink($tempFile);
