<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Submission;

class CheckSubmissionStatus extends Command
{
    protected $signature = 'check:submission-status';
    protected $description = 'Check submission status distribution';

    public function handle()
    {
        $this->info('=== Submission Status Distribution ===');
        
        $statuses = ['pending', 'approved', 'rejected', 'revision_needed', 'sold'];
        
        foreach ($statuses as $status) {
            $count = Submission::where('status', $status)->count();
            $this->info("{$status}: {$count}");
        }
        
        $this->info('');
        $this->info('Total submissions: ' . Submission::count());
        
        // Show latest few submissions
        $this->info('');
        $this->info('=== Latest 5 Submissions ===');
        $latest = Submission::latest()->take(5)->get();
        
        foreach ($latest as $submission) {
            $this->line("ID {$submission->id}: {$submission->product_name} - Status: {$submission->status}");
        }
    }
}
