<?php
$dsn = "odbc:DRIVER={HDBODBC};SERVERNODE=192.168.4.80:30015;DATABASE=TEST_DSL";
$user = "PABANIH";
$pass = "dwf6qyLDfr";

try {
    $dbh = new PDO($dsn, $user, $pass);
    
    echo "=== CHECKING YB_LOG TABLE ACCESS ===\n\n";
    
    // Try to read from YB_LOG
    echo "1. Testing SELECT on YB_LOG...\n";
    try {
        $sql = 'SELECT COUNT(*) as cnt FROM "TEST_DSL"."YB_LOG"';
        $stmt = $dbh->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "   ✓ SELECT works. Current row count: " . $row['cnt'] . "\n\n";
    } catch (PDOException $e) {
        echo "   ✗ SELECT failed: " . $e->getMessage() . "\n\n";
    }
    
    // Try to insert into YB_LOG
    echo "2. Testing INSERT on YB_LOG...\n";
    try {
        $insertSql = 'INSERT INTO "TEST_DSL"."YB_LOG" ("TRNTYPE", "REMARKS", "DTETME", "USRNME") '
            . 'VALUES (\'TEST\', \'Test Remark\', CURRENT_TIMESTAMP, \'TEST_USER\')';
        $stmt = $dbh->prepare($insertSql);
        $result = $stmt->execute();
        echo "   ✓ INSERT successful!\n";
        echo "   Rows affected: " . $stmt->rowCount() . "\n\n";
    } catch (PDOException $e) {
        echo "   ✗ INSERT failed: " . $e->getMessage() . "\n";
        echo "   Error Code: " . $e->getCode() . "\n\n";
    }
    
    // Check YB_LOG structure
    echo "3. YB_LOG Table Structure:\n";
    try {
        $sql = 'SELECT * FROM "TEST_DSL"."YB_LOG" LIMIT 1';
        $stmt = $dbh->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            echo "   Columns found: " . implode(', ', array_keys($row)) . "\n";
        } else {
            echo "   Table is empty (but exists and is readable)\n";
        }
    } catch (PDOException $e) {
        echo "   ✗ Failed to query: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
