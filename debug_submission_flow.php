<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUGGING SUBMISSION → PRODUCT FLOW ===\n\n";

// 1. Check recent submissions
echo "1. RECENT SUBMISSIONS:\n";
$submissions = DB::table('submissions')->latest()->limit(5)->get();
foreach ($submissions as $submission) {
    echo "Submission ID: {$submission->id}\n";
    echo "Status: {$submission->status}\n";
    echo "Images: {$submission->images}\n";
    echo "Product Name: {$submission->product_name}\n";
    echo "---\n";
}

// 2. Check recent products (likely from approved submissions)
echo "\n2. RECENT PRODUCTS:\n";
$products = DB::table('products')->latest()->limit(5)->get();
foreach ($products as $product) {
    echo "Product ID: {$product->id}\n";
    echo "Images: {$product->images}\n";
    echo "Created: {$product->created_at}\n";
    echo "---\n";
}

// 3. Test a specific product's image flow
echo "\n3. TESTING PRODUCT IMAGE FLOW:\n";
$testProduct = \InnoShop\Common\Models\Product::latest()->first();
if ($testProduct) {
    echo "Testing Product ID: {$testProduct->id}\n";
    echo "Product Name: " . ($testProduct->translation ? $testProduct->translation->name : 'No name') . "\n";
    echo "Raw images data: " . json_encode($testProduct->getRawOriginal('images')) . "\n";
    echo "Image accessor: {$testProduct->image}\n";
    echo "Image URL accessor: {$testProduct->image_url}\n";
    
    // Test file existence
    if ($testProduct->image) {
        $imagePath = $testProduct->image;
        
        // Test different possible paths
        $publicPath = public_path($imagePath);
        $storagePath = storage_path('app/public/' . str_replace('storage/', '', $imagePath));
        
        echo "Image path from accessor: {$imagePath}\n";
        echo "Public path: {$publicPath} - " . (file_exists($publicPath) ? "EXISTS" : "NOT FOUND") . "\n";
        echo "Storage path: {$storagePath} - " . (file_exists($storagePath) ? "EXISTS" : "NOT FOUND") . "\n";
        
        // Test ImageService
        try {
            $imageService = new \InnoShop\Common\Services\ImageService($imagePath);
            echo "ImageService originUrl(): " . $imageService->originUrl() . "\n";
        } catch (Exception $e) {
            echo "ImageService error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "No image found for this product\n";
    }
}

echo "\n=== END DEBUG ===\n";
