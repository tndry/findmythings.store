<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DebugSubmissionIssue extends Command
{
    protected $signature = 'debug:submission-issue';
    protected $description = 'Debug submission access issue';

    public function handle()
    {
        $this->info('=== Debugging Submission Access Issue ===');
        
        // Test 1: Check route registration
        $this->info('1. Checking route registration...');
        try {
            $url = route('submission.create');
            $this->info("✓ Route 'submission.create' resolves to: {$url}");
        } catch (\Exception $e) {
            $this->error("✗ Route 'submission.create' error: " . $e->getMessage());
        }
        
        // Test 2: Check middleware
        $this->info('');
        $this->info('2. Checking middleware configuration...');
        $router = app('router');
        $routes = $router->getRoutes();
        $submissionRoute = $routes->getByName('submission.create');
        
        if ($submissionRoute) {
            $middleware = $submissionRoute->gatherMiddleware();
            $this->info('Route middleware: ' . implode(', ', $middleware));
        }
        
        // Test 3: Check authentication status (dummy)
        $this->info('');
        $this->info('3. Current authentication requirements:');
        $this->info('- front middleware: Required for InnoShop pages');
        $this->info('- customer_auth:customer: Requires customer guard login');
        $this->info('- verified: Requires email verification');
        
        // Test 4: Check controller
        $this->info('');
        $this->info('4. Checking controller...');
        try {
            $controller = new \App\Http\Controllers\SubmissionController();
            $this->info('✓ SubmissionController can be instantiated');
            
            if (method_exists($controller, 'create')) {
                $this->info('✓ create() method exists');
            } else {
                $this->error('✗ create() method not found');
            }
        } catch (\Exception $e) {
            $this->error('✗ Controller error: ' . $e->getMessage());
        }
        
        $this->info('');
        $this->info('=== Recommendations ===');
        $this->info('1. Ensure user is logged in with customer guard');
        $this->info('2. Ensure email is verified (if using verified middleware)');
        $this->info('3. Check if front middleware is properly configured');
        $this->info('4. Test by removing middleware temporarily');
        
        $this->info('');
        $this->info('=== Debug Complete ===');
    }
}
