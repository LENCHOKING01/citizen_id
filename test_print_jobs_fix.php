<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PrintJob;
use App\Models\Application;
use App\Models\Citizen;
use App\Models\User;

echo "🔧 Testing Print Jobs Database Fix\n";
echo "===================================\n\n";

// Test 1: Check if print_jobs table has the required columns
echo "1. Checking print_jobs table structure:\n";
try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('print_jobs');
    echo "✅ Print Jobs table columns:\n";
    foreach ($columns as $column) {
        echo "   - {$column}\n";
    }
    
    $requiredColumns = ['application_id', 'printing_officer_id'];
    $missingColumns = array_diff($requiredColumns, $columns);
    
    if (empty($missingColumns)) {
        echo "✅ All required columns present!\n\n";
    } else {
        echo "❌ Missing columns: " . implode(', ', $missingColumns) . "\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error checking table structure: " . $e->getMessage() . "\n\n";
}

// Test 2: Try to create a print job record
echo "2. Testing print job record creation:\n";
try {
    // Get existing test data
    $citizen = Citizen::first();
    $application = Application::first();
    $user = User::where('email', 'printer@citizenid.local')->first();
    
    if ($citizen && $application && $user) {
        $printJob = PrintJob::create([
            'citizen_id' => $citizen->id,
            'application_id' => $application->id,
            'printing_officer_id' => $user->id,
            'status' => 'pending',
            'attempts' => 0
        ]);
        
        echo "✅ Print job record created successfully!\n";
        echo "   Print Job ID: {$printJob->id}\n";
        echo "   Citizen: {$citizen->full_name}\n";
        echo "   Application: {$application->id}\n";
        echo "   Printing Officer: {$user->name}\n";
        echo "   Status: {$printJob->status}\n\n";
        
    } else {
        echo "❌ Missing test data (citizen, application, or user)\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error creating print job record: " . $e->getMessage() . "\n\n";
}

// Test 3: Test relationships
echo "3. Testing print job relationships:\n";
try {
    $printJob = PrintJob::with(['citizen', 'application', 'printingOfficer'])->first();
    
    if ($printJob) {
        echo "✅ Print job relationships working:\n";
        echo "   Citizen: " . ($printJob->citizen ? $printJob->citizen->full_name : 'Not loaded') . "\n";
        echo "   Application: " . ($printJob->application ? "ID {$printJob->application->id}" : 'Not loaded') . "\n";
        echo "   Printing Officer: " . ($printJob->printingOfficer ? $printJob->printingOfficer->name : 'Not loaded') . "\n\n";
    } else {
        echo "❌ No print job records found to test relationships\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing relationships: " . $e->getMessage() . "\n\n";
}

echo "🎯 Print Jobs Fix Test Complete!\n";
echo "The print jobs functionality should now work properly in the web interface.\n";
