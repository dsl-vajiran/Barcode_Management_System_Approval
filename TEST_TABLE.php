<?php
$dsn = "odbc:DRIVER={HDBODBC};SERVERNODE=192.168.4.80:30015;DATABASE=TEST_DSL";
$user = "PABANIH";
$pass = "dwf6qyLDfr";

try {
    $dbh = new PDO($dsn, $user, $pass);
    $sql = 'SELECT * FROM "TEST_DSL"."YBISSUE" WHERE "IBARCODE" = \'5J2V29 13410479\' LIMIT 1';
    $stmt = $dbh->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "=== Raw Data ===\n";
    print_r($row);
    
    echo "\n=== Column Names ===\n";
    if ($row) {
        foreach (array_keys($row) as $colName) {
            echo "- " . $colName . "\n";
        }
    }
    
    echo "\n=== Key Column Values ===\n";
    echo "IBARCODE: " . ($row['IBARCODE'] ?? $row['ibarcode'] ?? 'NOT FOUND') . "\n";
    echo "INVNO: " . ($row['INVNO'] ?? $row['invno'] ?? 'NOT FOUND') . "\n";
    echo "ITMNME: " . ($row['ITMNME'] ?? $row['itmnme'] ?? 'NOT FOUND') . "\n";
    echo "ITMMOD: " . ($row['ITMMOD'] ?? $row['itmmod'] ?? 'NOT FOUND') . "\n";
    echo "SALEDTME: " . ($row['SALEDTME'] ?? $row['saledtme'] ?? 'NOT FOUND') . "\n";
    echo "FNCUSNM: " . ($row['FNCUSNM'] ?? $row['fncusnm'] ?? 'NOT FOUND') . "\n";
    echo "FNCUSTP: " . ($row['FNCUSTP'] ?? $row['fncustp'] ?? 'NOT FOUND') . "\n";
    echo "IREMARK: " . ($row['IREMARK'] ?? $row['iremark'] ?? 'NOT FOUND') . "\n";
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>