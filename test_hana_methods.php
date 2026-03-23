<?php
require 'vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== HANA Connection Test (Alternative Method) ===\n";
echo "Host: " . env('HANA_HOST') . "\n";
echo "Port: " . env('HANA_PORT') . "\n";
echo "Database: " . env('HANA_DATABASE') . "\n";
echo "User: " . env('HANA_USERNAME') . "\n\n";

try {
    // Try Method 1: ODBC DSN-less connection
    echo "Method 1: Direct HDBODBC Driver...\n";
    $dsn = 'odbc:Driver={HDBODBC};ServerNode=' . env('HANA_HOST') . ':' . env('HANA_PORT') . ';DATABASE=' . env('HANA_DATABASE') . ';';
    
    $pdo = new PDO($dsn, env('HANA_USERNAME'), env('HANA_PASSWORD'));
    echo "✓ Connection Successful with Method 1!\n\n";
    
    // Test query
    $result = $pdo->query('SELECT TOP 1 * FROM "' . env('HANA_SCHEMA') . '"."YBIMMST"');
    if ($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        echo "✓ YBIMMST table accessible!\n";
        echo "Sample columns: " . implode(", ", array_keys($row)) . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Method 1 Failed: " . $e->getMessage() . "\n\n";
    
    try {
        // Try Method 2: Using full driver path
        echo "Method 2: Using full driver path...\n";
        $dsn = 'odbc:Driver=C:\\Program Files\\sap\\hdbclient\\libodbcHDB.dll;ServerNode=' . env('HANA_HOST') . ':' . env('HANA_PORT') . ';DATABASE=' . env('HANA_DATABASE') . ';';
        
        $pdo = new PDO($dsn, env('HANA_USERNAME'), env('HANA_PASSWORD'));
        echo "✓ Connection Successful with Method 2!\n";
        
    } catch (Exception $e2) {
        echo "✗ Method 2 Failed: " . $e2->getMessage() . "\n\n";
        
        // Try Method 3: Via config from Laravel
        try {
            echo "Method 3: Via Laravel config...\n";
            
            $app = require_once 'bootstrap/app.php';
            $db = $app->make('db');
            
            $connection = $db->connection('hana');
            $result = $connection->select('SELECT 1 as test');
            
            echo "✓ Connection Successful with Method 3!\n";
            echo "Result: " . json_encode($result) . "\n";
            
        } catch (Exception $e3) {
            echo "✗ Method 3 Failed: " . $e3->getMessage() . "\n";
        }
    }
}
?>
