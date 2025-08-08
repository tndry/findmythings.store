<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DETAILED DEBUGGING FOR PRODUCT IMAGES ===\n\n";

// Cek product terbaru 
$product = \InnoShop\Common\Models\Product::orderBy('id', 'desc')->first();

if ($product) {
    echo "Latest Product ID: {$product->id}\n";
    echo "Submission ID: {$product->submission_id}\n";
    
    // Raw data dari database
    $rawImages = $product->getAttributes()['images'] ?? 'null';
    echo "Raw images from DB: " . $rawImages . "\n";
    
    // Hasil accessor
    $accessorImages = $product->images;
    echo "Images via accessor: " . json_encode($accessorImages) . "\n";
    
    // Manual decode
    $decodedImages = json_decode($rawImages, true);
    echo "Manual decode: " . json_encode($decodedImages) . "\n";
    
    // Cek apakah ada gambar file yang sebenarnya tersimpan
    if (is_array($decodedImages) && !empty($decodedImages)) {
        foreach ($decodedImages as $index => $imageData) {
            echo "Image $index: " . json_encode($imageData) . "\n";
            
            if (is_string($imageData)) {
                echo "  Type: String URL\n";
                echo "  Value: $imageData\n";
            } elseif (is_array($imageData)) {
                echo "  Type: Array\n";
                if (isset($imageData['url'])) {
                    echo "  URL: {$imageData['url']}\n";
                }
                if (isset($imageData['value'])) {
                    echo "  Value: {$imageData['value']}\n";
                    
                    // Cek apakah file benar-benar ada
                    $filePath = storage_path('app/public/catalog/' . $imageData['value']);
                    echo "  Full path: $filePath\n";
                    echo "  File exists: " . (file_exists($filePath) ? 'YES' : 'NO') . "\n";
                }
            }
        }
    }
    
} else {
    echo "No products found\n";
}
