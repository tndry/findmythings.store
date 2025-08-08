<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUGGING IMAGE ISSUES ===\n\n";

// Find products with images
$products = DB::table('products')->whereNotNull('images')->where('images', '!=', '[]')->get();

foreach ($products as $dbProduct) {
    $productModel = \InnoShop\Common\Models\Product::find($dbProduct->id);
    
    echo "--- Product ID: {$dbProduct->id} ---\n";
    echo "DB images raw: {$dbProduct->images}\n";
    echo "Model->image: " . $productModel->image . "\n";
    echo "Model->image_url: " . $productModel->image_url . "\n";
    
    // Test image_resize directly
    if ($productModel->image) {
        echo "image_resize(image, 300, 300): " . image_resize($productModel->image, 300, 300) . "\n";
    }
    
    echo "\n";
    
    // Only check first 3 products
    if ($dbProduct->id > 82) break;
}

echo "=== TESTING SPECIFIC IMAGE PATH ===\n";
$testPath = 'storage/catalog/products/23NJeo25oWPHMrKb0YXkkiGtgNYlqfd1q9bhc3ho.jpg';
echo "Testing path: $testPath\n";

try {
    $imageService = new \InnoShop\Common\Services\ImageService($testPath);
    echo "ImageService originUrl(): " . $imageService->originUrl() . "\n";
    echo "ImageService resize(300,300): " . $imageService->resize(300, 300) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== FILE EXISTENCE CHECK ===\n";
$storagePath = storage_path('app/public/catalog/products/23NJeo25oWPHMrKb0YXkkiGtgNYlqfd1q9bhc3ho.jpg');
echo "Storage path: $storagePath\n";
echo "File exists: " . (file_exists($storagePath) ? "YES" : "NO") . "\n";
