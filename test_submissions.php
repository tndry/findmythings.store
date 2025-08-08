<?php
require 'vendor/autoload.php';
\ = require 'bootstrap/app.php';
\->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

echo " Testing Submissions:\\n\;
echo \Count: \ . App\\Models\\Submission::count() . \\\n\;

\ = App\\Models\\Submission::latest()->limit(3)->get();
foreach(\ as \) {
 echo \ID: \$submission->id Title: \$submission->title Status: -encodedCommand XAAkAHMAdQBiAG0AaQBzAHMAaQBvAG4ALQA+AHMAdABhAHQAdQBzAA== \\n\;
}
EOF -inputFormat xml -outputFormat text
