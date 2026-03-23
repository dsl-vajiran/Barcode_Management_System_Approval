<?php
require 'vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== HANA Connection Test ===\n";
echo "Host: " . $_ENV['HANA_HOST'] . "\n";
echo "Port: " . $_ENV['HANA_PORT'] . "\n";
echo "Database: " . $_ENV['HANA_DATABASE'] . "\n";
echo "User: " . $_ENV['HANA_USERNAME'] . "\n";
echo "Schema: " . ($_ENV['HANA_SCHEMA'] ?? 'not set') . "\n\n";

try {
    $host = $_ENV['HANA_HOST'];
    $port = $_ENV['HANA_PORT'];
    $username = $_ENV['HANA_USERNAME'];
    $password = $_ENV['HANA_PASSWORD'];
    $schema = $_ENV['HANA_SCHEMA'] ?? null;
    
    $dsn = "odbc:Driver={HDBODBC};ServerNode={$host}:{$port};UID={$username};PWD={$password};";
    
    echo "Attempting ODBC connection to SAP HANA...\n";
    echo "DSN: odbc:Driver={HDBODBC};ServerNode={$host}:{$port}\n\n";
    
    $pdo = new PDO($dsn, null, null);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ HANA Connection Successful!\n\n";
    
    // Set schema if specified
    if ($schema && !empty($schema)) {
        echo "Setting schema to: {$schema}\n";
        $pdo->exec("SET SCHEMA \"{$schema}\"");
        echo "✓ Schema set successfully!\n\n";
    }
    
    // Try to get current schema
    echo "Testing query execution...\n";
    $result = $pdo->query('SELECT CURRENT_SCHEMA FROM DUMMY');
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo "✓ Current Schema: " . $row['CURRENT_SCHEMA'] . "\n\n";
    
    // Try to list some tables
    echo "Listing available tables in schema...\n";
    $tableQuery = "SELECT TOP 10 TABLE_NAME FROM SYS.TABLES WHERE SCHEMA_NAME = '{$schema}' ORDER BY TABLE_NAME";
    $result = $pdo->query($tableQuery);
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "✓ Found " . count($tables) . " tables:\n";
        foreach ($tables as $table) {
            echo "   - {$table}\n";
        }
    } else {
        echo "No tables found in schema {$schema}\n";
    }
    
    echo "\n=== Connection Test Complete ===\n";
    
} catch (PDOException $e) {
    echo "✗ Connection Failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    
    echo "\n--- Troubleshooting Tips ---\n";
    echo "1. Ensure SAP HANA Client is installed\n";
    echo "2. Verify HDBODBC driver is available in ODBC Data Sources\n";
    echo "3. Check if the server is accessible from this machine\n";
    echo "4. Verify credentials are correct\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
