<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dsn = "odbc:Driver={HDBODBC};ServerNode={$_ENV['HANA_HOST']}:{$_ENV['HANA_PORT']};";
$pdo = new PDO($dsn, $_ENV['HANA_USERNAME'], $_ENV['HANA_PASSWORD']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET SCHEMA \"{$_ENV['HANA_SCHEMA']}\"");

echo "Connection: OK\n";
echo "Schema: {$_ENV['HANA_SCHEMA']}\n";
echo "\n=== SAP HANA Connection Test PASSED ===\n";
