<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use InnoShop\Common\Models\Product;
use App\Models\Submission;

class CheckDuplicateProducts extends Command
{
    protected $signature = 'check:duplicate-products {--fix : Remove duplicate products}';
    protected $description = 'Check for duplicate products from submissions';

    public function handle()
    {
        $this->info('=== Checking for Duplicate Products ===');
        
        // 1. Cari produk yang memiliki submission_id sama
        $duplicateGroups = Product::select('submission_id')
            ->whereNotNull('submission_id')
            ->groupBy('submission_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();
            
        if ($duplicateGroups->isEmpty()) {
            $this->info('✓ No duplicate products found.');
        } else {
            $this->error("Found {$duplicateGroups->count()} submission(s) with duplicate products:");
            
            $totalDuplicates = 0;
            
            foreach ($duplicateGroups as $group) {
                $products = Product::where('submission_id', $group->submission_id)->get();
                $submission = Submission::find($group->submission_id);
                
                $submissionName = $submission ? $submission->product_name : 'Unknown';
                $this->info("Submission ID {$group->submission_id} ({$submissionName}):");
                $this->info("  - Has {$products->count()} products (should be 1)");
                
                foreach ($products as $index => $product) {
                    $status = $index === 0 ? 'KEEP' : 'DUPLICATE';
                    $this->line("    Product ID {$product->id} - {$status}");
                    if ($index > 0) $totalDuplicates++;
                }
                $this->line('');
            }
            
            $this->info("Total duplicate products to remove: {$totalDuplicates}");
            
            if ($this->option('fix')) {
                $this->info('Removing duplicate products...');
                
                $removed = 0;
                foreach ($duplicateGroups as $group) {
                    $products = Product::where('submission_id', $group->submission_id)
                        ->orderBy('id', 'asc')
                        ->get();
                    
                    // Keep the first product, remove the rest
                    foreach ($products->skip(1) as $product) {
                        $this->line("Removing duplicate product ID {$product->id}");
                        $product->delete();
                        $removed++;
                    }
                }
                
                $this->info("✓ Removed {$removed} duplicate products.");
            } else {
                $this->info('');
                $this->info('To remove duplicates, run: php artisan check:duplicate-products --fix');
            }
        }
        
        // 2. Cari submission yang approved tapi tidak ada produknya
        $this->info('');
        $this->info('=== Checking for Approved Submissions without Products ===');
        
        $approvedSubmissions = Submission::where('status', 'approved')->get();
        $orphanSubmissions = [];
        
        foreach ($approvedSubmissions as $submission) {
            $hasProduct = Product::where('submission_id', $submission->id)->exists();
            if (!$hasProduct) {
                $orphanSubmissions[] = $submission;
            }
        }
            
        if (empty($orphanSubmissions)) {
            $this->info('✓ All approved submissions have products.');
        } else {
            $this->error("Found " . count($orphanSubmissions) . " approved submission(s) without products:");
            foreach ($orphanSubmissions as $submission) {
                $this->line("  - Submission ID {$submission->id}: {$submission->product_name}");
            }
        }
        
        $this->info('');
        $this->info('=== Check Complete ===');
    }
}
