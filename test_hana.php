<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing HANA connection...\n";

try {
    $db = $app->make('db');
    $conn = $db->connection('hana');

    // Get underlying PDO
    $pdo = $conn->getPdo();
    echo "✓ PDO connected\n";

    // Simple query
    $rows = $conn->table('YBGRN')->limit(1)->get();
    echo "✓ Query result: " . json_encode($rows) . "\n";

} catch (Throwable $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
