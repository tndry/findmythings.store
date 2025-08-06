<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use full namespace
$product = \InnoShop\Common\Models\Product::latest()->first();
if ($product) {
    echo "Product ID: " . $product->id . "\n";
    echo "Product Name: " . $product->name . "\n";
    echo "Raw Images: " . $product->getRawOriginal('images') . "\n";
    echo "Images Type: " . gettype($product->images) . "\n";
    echo "Images Content: " . var_export($product->images, true) . "\n";
    
    if (is_array($product->images)) {
        echo "Images Count: " . count($product->images) . "\n";
        foreach($product->images as $index => $image) {
            echo "Image $index: " . var_export($image, true) . " (type: " . gettype($image) . ")\n";
        }
    }
} else {
    echo "No products found\n";
}
