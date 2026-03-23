<?php
/**
 * SAP HANA Database Connection Test Script
 * This script tests the connection to SAP Business One HANA database
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "==============================================\n";
echo "SAP HANA Database Connection Test\n";
echo "==============================================\n\n";

// Display connection configuration
echo "Connection Configuration:\n";
echo "------------------------\n";
echo "Connection: hana\n";
echo "Host: " . env('HANA_HOST') . "\n";
echo "Port: " . env('HANA_PORT') . "\n";
echo "Database: " . env('HANA_DATABASE') . "\n";
echo "Schema: " . env('HANA_SCHEMA') . "\n";
echo "Username: " . env('HANA_USERNAME') . "\n";
echo "Password: " . (env('HANA_PASSWORD') ? '***' . substr(env('HANA_PASSWORD'), -2) : 'Not Set') . "\n";
echo "\n";

try {
    echo "Testing connection...\n";
    
    // Test basic connection
    $pdo = DB::connection('hana')->getPdo();
    echo "✓ Successfully connected to the database!\n\n";
    
    // Try to get database version
    echo "Getting database information...\n";
    try {
        $version = DB::select("SELECT VERSION FROM SYS.M_DATABASE LIMIT 1");
        if (!empty($version)) {
            echo "✓ Database Version: " . json_encode($version[0]) . "\n";
        }
    } catch (Exception $e) {
        echo "⚠ Could not retrieve version: " . $e->getMessage() . "\n";
    }
    
    // Try to get schema information
    echo "\nChecking schema access...\n";
    try {
        $schema = env('HANA_SCHEMA');
        $tables = DB::connection('hana')->select("SELECT TABLE_NAME FROM SYS.TABLES WHERE SCHEMA_NAME = ? LIMIT 10", [$schema]);
        echo "✓ Schema access successful!\n";
        echo "Found " . count($tables) . " tables (showing first 10):\n";
        foreach ($tables as $table) {
            echo "  - " . $table->TABLE_NAME . "\n";
        }
    } catch (Exception $e) {
        echo "⚠ Could not retrieve schema info: " . $e->getMessage() . "\n";
    }
    
    // Check for SAP B1 specific tables
    echo "\nChecking for SAP Business One tables...\n";
    try {
        $b1Tables = ['OITM', 'OIGN', 'OWOR', 'ORDR', 'OINV'];
        $found = [];
        
        foreach ($b1Tables as $tableName) {
            $result = DB::connection('hana')->select("SELECT COUNT(*) as cnt FROM SYS.TABLES WHERE SCHEMA_NAME = ? AND TABLE_NAME = ?",
                [$schema, $tableName]);
            if ($result[0]->cnt > 0) {
                $found[] = $tableName;
            }
        }
        
        if (!empty($found)) {
            echo "✓ Found SAP B1 tables: " . implode(', ', $found) . "\n";
        } else {
            echo "⚠ No standard SAP B1 tables found. This might be a custom schema.\n";
        }
    } catch (Exception $e) {
        echo "⚠ Could not check SAP B1 tables: " . $e->getMessage() . "\n";
    }
    
    // Check for custom tables (YBGRN, YBIMMST, etc.)
    echo "\nChecking for custom barcode tables...\n";
    try {
        $customTables = ['YBGRN', 'YBIMMST', 'YBISMANU', 'YBISSUE', 'YBGRTN'];
        $found = [];
        
        foreach ($customTables as $tableName) {
            $result = DB::connection('hana')->select("SELECT COUNT(*) as cnt FROM SYS.TABLES WHERE SCHEMA_NAME = ? AND TABLE_NAME = ?",
                [$schema, $tableName]);
            if ($result[0]->cnt > 0) {
                $found[] = $tableName;
            }
        }
        
        if (!empty($found)) {
            echo "✓ Found custom tables: " . implode(', ', $found) . "\n";
        } else {
            echo "⚠ Custom barcode tables not found. You may need to create them.\n";
        }
    } catch (Exception $e) {
        echo "⚠ Could not check custom tables: " . $e->getMessage() . "\n";
    }
    
    echo "\n==============================================\n";
    echo "Connection Test: SUCCESS ✓\n";
    echo "==============================================\n";
    
} catch (Exception $e) {
    echo "\n==============================================\n";
    echo "Connection Test: FAILED ✗\n";
    echo "==============================================\n";
    echo "\nError Details:\n";
    echo "Type: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
    
    echo "\n==============================================\n";
    echo "Troubleshooting Tips:\n";
    echo "==============================================\n";
    echo "1. Ensure SAP HANA ODBC driver is installed\n";
    echo "2. Verify the connection parameters in .env file\n";
    echo "3. Check network connectivity to the database server\n";
    echo "4. Verify user credentials and permissions\n";
    echo "5. Check if the schema exists and user has access\n";
    echo "6. Review PHP extensions (odbc, pdo_odbc)\n";
    echo "\n";
    
    exit(1);
}
