<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GoogleLoginController;

class TestGoogleController extends Command
{
    protected $signature = 'test:google-controller';
    protected $description = 'Test Google OAuth controller methods';

    public function handle()
    {
        $this->info('=== Testing GoogleLoginController ===');
        
        try {
            $controller = new GoogleLoginController();
            $this->info('✓ Controller created successfully');
            
            $methods = get_class_methods($controller);
            $this->info('Available methods: ' . implode(', ', $methods));
            
            if (in_array('redirect', $methods)) {
                $this->info('✓ redirect() method found');
            } else {
                $this->error('✗ redirect() method NOT found');
            }
            
            if (in_array('callback', $methods)) {
                $this->info('✓ callback() method found');
            } else {
                $this->error('✗ callback() method NOT found');
            }
            
        } catch (\Exception $e) {
            $this->error('Error creating controller: ' . $e->getMessage());
        }
        
        $this->info('=== Test Complete ===');
    }
}
