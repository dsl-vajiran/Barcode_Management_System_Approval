<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== HANA Connection Test ===" . PHP_EOL;
echo "Host: " . $_ENV['HANA_HOST'] . PHP_EOL;
echo "Port: " . $_ENV['HANA_PORT'] . PHP_EOL;
echo "User: " . $_ENV['HANA_USERNAME'] . PHP_EOL;
echo "Schema: " . $_ENV['HANA_SCHEMA'] . PHP_EOL;
echo PHP_EOL;

$host = $_ENV['HANA_HOST'];
$port = $_ENV['HANA_PORT'];
$username = $_ENV['HANA_USERNAME'];
$password = $_ENV['HANA_PASSWORD'];
$schema = $_ENV['HANA_SCHEMA'];

$dsn = "odbc:Driver={HDBODBC};ServerNode={$host}:{$port};UID={$username};PWD={$password};";

echo "Attempting connection..." . PHP_EOL;

try {
    $pdo = new PDO($dsn, null, null);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "SUCCESS: Connected to SAP HANA!" . PHP_EOL;
    
    if ($schema) {
        $pdo->exec("SET SCHEMA \"$schema\"");
        echo "Schema set to: $schema" . PHP_EOL;
    }
    
    $result = $pdo->query('SELECT CURRENT_SCHEMA FROM DUMMY');
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo "Current Schema: " . $row['CURRENT_SCHEMA'] . PHP_EOL;
    
} catch (PDOException $e) {
    echo "FAILED: " . $e->getMessage() . PHP_EOL;
}
