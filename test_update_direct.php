<?php
$dsn = "odbc:DRIVER={HDBODBC};SERVERNODE=192.168.4.80:30015;DATABASE=TEST_DSL";
$user = "PABANIH";
$pass = "dwf6qyLDfr";

try {
    $dbh = new PDO($dsn, $user, $pass);
    
    echo "=== BEFORE UPDATE ===\n";
    $sql = 'SELECT IBARCODE, SALEDTME, FNCUSNM, FNCUSTP, IREMARK, ICHAPR, IAPRDTE FROM "TEST_DSL"."YBISSUE" WHERE "IBARCODE" = \'5J2V29 13270424\'';
    $stmt = $dbh->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($row);
    
    echo "\n=== TESTING UPDATE ===\n";
    
    // Try updating with UPPERCASE column names
    $updateSql = 'UPDATE "TEST_DSL"."YBISSUE" SET '
        . '"SALEDTME" = \'2026-02-20 15:30:00\', '
        . '"FNCUSNM" = \'Test Customer\', '
        . '"FNCUSTP" = \'123456789\', '
        . '"IREMARK" = \'Test Remark\', '
        . '"ICHAPR" = 1, '
        . '"IAPRDTE" = \'2026-02-20 15:30:00\' '
        . 'WHERE "IBARCODE" = \'5J2V29 13270424\'';
    
    $stmt = $dbh->prepare($updateSql);
    $result = $stmt->execute();
    echo "Update executed: " . ($result ? "SUCCESS" : "FAILED") . "\n";
    echo "Rows affected: " . $stmt->rowCount() . "\n";
    
    echo "\n=== AFTER UPDATE ===\n";
    $sql = 'SELECT IBARCODE, SALEDTME, FNCUSNM, FNCUSTP, IREMARK, ICHAPR, IAPRDTE FROM "TEST_DSL"."YBISSUE" WHERE "IBARCODE" = \'5J2V29 13270424\'';
    $stmt = $dbh->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($row);
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
