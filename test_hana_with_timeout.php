<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== HANA Connection Test (with timeout) ===" . PHP_EOL;
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

// DSN with connection timeout
$dsn = "odbc:Driver={HDBODBC};ServerNode={$host}:{$port};";

echo "DSN: odbc:Driver={HDBODBC};ServerNode={$host}:{$port};" . PHP_EOL;
echo PHP_EOL;

echo "Attempting connection..." . PHP_EOL;
$startTime = microtime(true);

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 10  // 10 second timeout
    ]);
    
    $elapsed = round(microtime(true) - $startTime, 2);
    echo "SUCCESS: Connected to SAP HANA! (took {$elapsed}s)" . PHP_EOL;
    
    if ($schema) {
        $pdo->exec("SET SCHEMA \"$schema\"");
        echo "Schema set to: $schema" . PHP_EOL;
    }
    
    // Test query
    $result = $pdo->query('SELECT CURRENT_SCHEMA, CURRENT_USER FROM DUMMY');
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo "Current Schema: " . $row['CURRENT_SCHEMA'] . PHP_EOL;
    echo "Current User: " . $row['CURRENT_USER'] . PHP_EOL;
    
    echo PHP_EOL . "=== Connection Test PASSED ===" . PHP_EOL;
    
} catch (PDOException $e) {
    $elapsed = round(microtime(true) - $startTime, 2);
    echo "FAILED after {$elapsed}s: " . $e->getMessage() . PHP_EOL;
    echo "Error Code: " . $e->getCode() . PHP_EOL;
}
