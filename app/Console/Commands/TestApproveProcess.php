<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Submission;
use App\Http\Controllers\Admin\SubmissionController;

class TestApproveProcess extends Command
{
    protected $signature = 'test:approve-process {submission_id}';
    protected $description = 'Test approval process for a specific submission';

    public function handle()
    {
        $submissionId = $this->argument('submission_id');
        $submission = Submission::find($submissionId);
        
        if (!$submission) {
            $this->error("Submission with ID {$submissionId} not found.");
            return;
        }
        
        $this->info("Testing approval for Submission ID {$submissionId}: {$submission->product_name}");
        $this->info("Current status: {$submission->status}");
        
        if ($submission->status === 'approved') {
            $this->error("This submission is already approved. Resetting to pending for test...");
            $submission->status = 'pending';
            $submission->save();
        }
        
        try {
            // Test the approve method
            $controller = new SubmissionController();
            $this->info('Attempting to approve submission...');
            
            // Simulate the approve process
            $result = $controller->approve($submission);
            
            $this->info('Approval process completed successfully!');
            
            // Check if product was created
            $product = \InnoShop\Common\Models\Product::where('submission_id', $submission->id)->first();
            if ($product) {
                $this->info("✓ Product created successfully (ID: {$product->id})");
            } else {
                $this->error("✗ No product was created");
            }
            
        } catch (\Exception $e) {
            $this->error('Approval process failed: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
            $this->error('Trace: ' . $e->getTraceAsString());
        }
    }
}
