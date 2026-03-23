<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

try {
    // Test the optimized query
    $issue = App\Models\Ybissue::select('IBARCODE', 'INVNO', 'ICHAPR')->where('IBARCODE', '5J2V29 13270424')->first();
    
    if ($issue) {
        echo "✓ Success!\n";
        echo "Barcode: " . $issue->ibarcode . "\n";
        echo "Invoice: " . $issue->invno . "\n";
        echo "Status: " . ($issue->ichapr == 1 ? "Already approved" : "Pending") . "\n";
    } else {
        echo "✗ Barcode not found\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
