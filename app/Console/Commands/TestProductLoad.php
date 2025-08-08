<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use InnoShop\Common\Models\Product;
use App\Models\Submission;

class TestProductLoad extends Command
{
    protected $signature = 'test:product-load';
    protected $description = 'Test product loading and submission relation';

    public function handle()
    {
        $this->info('=== Testing Product Load ===');
        
        try {
            // Test 1: Check if we can load products
            $productCount = Product::count();
            $this->info("✓ Found {$productCount} products in database");
            
            // Test 2: Check if we can load submissions
            $submissionCount = Submission::count();
            $this->info("✓ Found {$submissionCount} submissions in database");
            
            // Test 3: Try to load a product with submission
            $product = Product::with('submission')->first();
            if ($product) {
                $this->info("✓ Loaded product: {$product->name}");
                if ($product->submission) {
                    $this->info("✓ Product has submission: {$product->submission->id}");
                } else {
                    $this->info("- Product has no submission");
                }
            } else {
                $this->error("✗ No products found");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . ":" . $e->getLine());
        }
        
        $this->info('=== Test Complete ===');
    }
}
