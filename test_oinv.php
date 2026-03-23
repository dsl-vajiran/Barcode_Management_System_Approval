<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing OINV Table ===\n\n";

try {
    // Test 1: Check specific invoice
    echo "Test 1: Checking Invoice 20032304\n";
    echo str_repeat("-", 50) . "\n";
    
    $invoice = DB::connection('hana')
        ->table('OINV')
        ->where('DocNum', '20032304')
        ->first();
    
    if ($invoice) {
        echo "✓ Invoice Found!\n";
        echo "DocNum: " . ($invoice->docnum ?? 'N/A') . "\n";
        echo "CardName: " . ($invoice->cardname ?? 'N/A') . "\n";
        echo "CardCode: " . ($invoice->cardcode ?? 'N/A') . "\n";
        echo "DocDate: " . ($invoice->docdate ?? 'N/A') . "\n";
        echo "DocTotal: " . ($invoice->doctotal ?? 'N/A') . "\n";
    } else {
        echo "✗ Invoice NOT Found\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
    
    // Test 2: Check the barcode in YBISSUE
    echo "Test 2: Checking Barcode '5J2V29 13420480' in YBISSUE\n";
    echo str_repeat("-", 50) . "\n";
    
    $issue = DB::connection('hana')
        ->table('YBISSUE')
        ->where('IBARCODE', '5J2V29 13420480')
        ->first();
    
    if ($issue) {
        echo "✓ Barcode Found!\n";
        echo "IBARCODE: " . ($issue->ibarcode ?? 'N/A') . "\n";
        echo "INVNO: " . ($issue->invno ?? 'N/A') . "\n";
        echo "ICHAPR: " . ($issue->ichapr ?? 'N/A') . "\n";
        echo "ITMNME: " . ($issue->itmnme ?? 'N/A') . "\n";
    } else {
        echo "✗ Barcode NOT Found\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
    
    // Test 3: Join YBISSUE with OINV
    echo "Test 3: Joining YBISSUE with OINV for barcode\n";
    echo str_repeat("-", 50) . "\n";
    
    $joined = DB::connection('hana')
        ->table('YBISSUE as y')
        ->leftJoin('OINV as o', 'y.INVNO', '=', 'o.DocNum')
        ->where('y.IBARCODE', '5J2V29 13420480')
        ->select('y.IBARCODE', 'y.INVNO', 'o.CardName', 'o.CardCode', 'y.ICHAPR')
        ->first();
    
    if ($joined) {
        echo "✓ Joined Data Found!\n";
        echo "IBARCODE: " . ($joined->ibarcode ?? 'N/A') . "\n";
        echo "INVNO: " . ($joined->invno ?? 'N/A') . "\n";
        echo "CardName: " . ($joined->cardname ?? 'N/A') . "\n";
        echo "CardCode: " . ($joined->cardcode ?? 'N/A') . "\n";
        echo "ICHAPR: " . ($joined->ichapr ?? 'N/A') . "\n";
    } else {
        echo "✗ No joined data found\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
    
    // Test 4: Check OINV table structure
    echo "Test 4: OINV Table Structure (First 5 columns)\n";
    echo str_repeat("-", 50) . "\n";
    
    $sampleInvoice = DB::connection('hana')
        ->table('OINV')
        ->first();
    
    if ($sampleInvoice) {
        echo "✓ Sample columns from OINV:\n";
        $columns = array_keys(get_object_vars($sampleInvoice));
        foreach (array_slice($columns, 0, 10) as $column) {
            echo "  - " . $column . "\n";
        }
    } else {
        echo "✗ No data in OINV table\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
    
    // Test 5: Search for any records with similar DocNum
    echo "Test 5: Searching for invoices starting with '2003'\n";
    echo str_repeat("-", 50) . "\n";
    
    $similarInvoices = DB::connection('hana')
        ->table('OINV')
        ->where('DocNum', 'like', '2003%')
        ->select('DocNum', 'CardName')
        ->limit(5)
        ->get();
    
    if ($similarInvoices->count() > 0) {
        echo "✓ Found " . $similarInvoices->count() . " invoices:\n";
        foreach ($similarInvoices as $inv) {
            echo "  DocNum: " . $inv->docnum . " | CardName: " . $inv->cardname . "\n";
        }
    } else {
        echo "✗ No invoices found starting with '2003'\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
    
    // Test 6: Check any YBISSUE records
    echo "Test 6: Checking for any YBISSUE records\n";
    echo str_repeat("-", 50) . "\n";
    
    $anyIssue = DB::connection('hana')
        ->table('YBISSUE')
        ->select('IBARCODE', 'INVNO', 'ICHAPR')
        ->limit(3)
        ->get();
    
    if ($anyIssue->count() > 0) {
        echo "✓ Found " . $anyIssue->count() . " records in YBISSUE:\n";
        foreach ($anyIssue as $issue) {
            echo "  IBARCODE: " . $issue->ibarcode . " | INVNO: " . $issue->invno . " | ICHAPR: " . $issue->ichapr . "\n";
        }
    } else {
        echo "✗ No records found in YBISSUE\n";
    }
    echo "\n" . str_repeat("=", 50) . "\n\n";
    
    // Test 7: Check if INVNO 20032304 maps to DocNum 200304
    echo "Test 7: Testing INVNO to DocNum mapping\n";
    echo str_repeat("-", 50) . "\n";
    
    $invno = '20032304';
    
    // Try direct match
    $direct = DB::connection('hana')
        ->table('OINV')
        ->where('DocNum', $invno)
        ->first();
    
    echo "Direct match (DocNum = '$invno'): " . ($direct ? "Found" : "Not Found") . "\n";
    
    // Try without leading '20'
    $stripped = substr($invno, 2);
    $stripped_result = DB::connection('hana')
        ->table('OINV')
        ->where('DocNum', $stripped)
        ->first();
    
    echo "Stripped match (DocNum = '$stripped'): " . ($stripped_result ? "Found - CardName: " . $stripped_result->cardname : "Not Found") . "\n";
    
    // Try as integer (might remove leading zeros)
    $asInt = intval($invno);
    $int_result = DB::connection('hana')
        ->table('OINV')
        ->where('DocNum', $asInt)
        ->first();
    
    echo "Integer match (DocNum = $asInt): " . ($int_result ? "Found - CardName: " . $int_result->cardname : "Not Found") . "\n";
    
    // Check if DocNum 200304 exists
    echo "\n";
    
    // Test 8: Direct search for 20032304 as integer
    echo "Test 8: Searching for invoice 20032304 (as integer)\n";
    echo str_repeat("-", 50) . "\n";
    
    $invoice8digit = DB::connection('hana')
        ->table('OINV')
        ->where('DocNum', 20032304)
        ->first();
    
    if ($invoice8digit) {
        echo "✓ Found invoice 20032304!\n";
        echo "DocEntry: " . $invoice8digit->docentry . "\n";
        echo "DocNum: " . $invoice8digit->docnum . "\n";
        echo "CardName: " . $invoice8digit->cardname . "\n";
        echo "CardCode: " . $invoice8digit->cardcode . "\n";
    } else {
        echo "✗ Invoice 20032304 not found as DocNum\n";
        
        // Try Ref1 field
        $invoiceByRef = DB::connection('hana')
            ->table('OINV')
            ->where('Ref1', '20032304')
            ->first();
        
        if ($invoiceByRef) {
            echo "✓ Found in Ref1 field!\n";
            echo "DocNum: " . $invoiceByRef->docnum . "\n";
            echo "Ref1: " . $invoiceByRef->ref1 . "\n";
            echo "CardName: " . $invoiceByRef->cardname . "\n";
        } else {
            echo "✗ Not found in Ref1 either\n";
        }
    }
    echo "\n" . str_repeat("=", 50) . "\n\n";
    
    // Test 9: Check format issues between INVNO and DocNum
    echo "Test 9: Analyzing format differences\n";
    echo str_repeat("-", 50) . "\n";
    
    // Check INVNO format in YBISSUE
    $ybissueWithInv = DB::connection('hana')
        ->table('YBISSUE')
        ->whereNotNull('INVNO')
        ->where('INVNO', '!=', '')
        ->select('IBARCODE', 'INVNO')
        ->limit(10)
        ->get();
    
    echo "YBISSUE INVNO formats:\n";
    foreach ($ybissueWithInv as $item) {
        $invno = $item->invno;
        echo "  INVNO: '$invno' | Length: " . strlen($invno) . " | Type: " . gettype($invno) . "\n";
    }
    
    echo "\n";
    
    // Check DocNum format in OINV
    $oinvSamples = DB::connection('hana')
        ->table('OINV')
        ->select('DocNum', 'CardName')
        ->limit(10)
        ->get();
    
    echo "OINV DocNum formats:\n";
    foreach ($oinvSamples as $item) {
        $docnum = $item->docnum;
        echo "  DocNum: '$docnum' | Length: " . strlen($docnum) . " | Type: " . gettype($docnum) . "\n";
    }
    
    echo "\n";
    
    // Search for any 8-digit DocNum in OINV
    echo "Searching for specific DocNum 20032304 in OINV:\n";
    
    $specificInv = DB::connection('hana')
        ->table('OINV')
        ->where('DocNum', '20032304')
        ->first();
    
    if ($specificInv) {
        echo "✓ Found DocNum 20032304!\n";
        echo "  DocNum: " . $specificInv->docnum . "\n";
        echo "  CardName: " . $specificInv->cardname . "\n";
        echo "  CardCode: " . $specificInv->cardcode . "\n";
    } else {
        echo "✗ DocNum 20032304 not found in OINV\n";
        
        // Check all DocNum values that start with '200'
        echo "\nSearching for DocNum starting with '200':\n";
        $docsWith200 = DB::connection('hana')
            ->table('OINV')
            ->whereRaw("DocNum LIKE '200%'")
            ->select('DocNum', 'CardName')
            ->limit(10)
            ->get();
        
        if ($docsWith200->count() > 0) {
            echo "✓ Found " . $docsWith200->count() . " invoices:\n";
            foreach ($docsWith200 as $inv) {
                echo "  DocNum: " . $inv->docnum . " | CardName: " . $inv->cardname . "\n";
                
                // Check if any match our INVNO
                if ($inv->docnum == '20032304') {
                    echo "    ⭐ THIS MATCHES OUR INVNO!\n";
                }
            }
        } else {
            echo "✗ No DocNum starting with '200' found\n";
            echo "\nMost recent OINV entries:\n";
            $recent = DB::connection('hana')
                ->table('OINV')
                ->orderBy('DocEntry', 'DESC')
                ->select('DocNum', 'CardName', 'DocDate')
                ->limit(5)
                ->get();
            
            foreach ($recent as $inv) {
                echo "  DocNum: " . $inv->docnum . " | CardName: " . $inv->cardname . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n=== Test Complete ===\n";
