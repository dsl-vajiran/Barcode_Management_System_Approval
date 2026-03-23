<?php

namespace App\Services;

use PDO;
use PDOException;

class HanaService
{
    protected ?PDO $pdo = null;
    protected string $host;
    protected string $port;
    protected string $username;
    protected string $password;
    protected string $schema;

    public function __construct()
    {
        $this->host = env('HANA_HOST', '192.168.4.80');
        $this->port = env('HANA_PORT', '30015');
        $this->username = env('HANA_USERNAME', '');
        $this->password = env('HANA_PASSWORD', '');
        $this->schema = env('HANA_SCHEMA', 'TEST_DSL');
    }

    /**
     * Get the PDO connection to HANA
     */
    protected function getConnection(): PDO
    {
        if ($this->pdo === null) {
            $dsn = "odbc:Driver={HDBODBC};ServerNode={$this->host}:{$this->port};";
            
            $this->pdo = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_CASE => PDO::CASE_UPPER,
            ]);

            // Set the schema
            if ($this->schema) {
                $this->pdo->exec("SET SCHEMA \"{$this->schema}\"");
            }
        }

        return $this->pdo;
    }

    /**
     * Search items in YBIMMST table
     * 
     * @param string|null $search Search term
     * @param int $limit Number of records to return
     * @param int $offset Offset for pagination
     * @return array List of items
     */
    public function searchItems(?string $search = null, int $limit = 20, int $offset = 0): array
    {
        $pdo = $this->getConnection();

        $sql = "SELECT ITMCODE, ITMNME, ITMMOD, ITMAMP, F_WAR, PA_WAR, REMARK, BRAND 
                FROM YBIMMST
                WHERE ITMCODE IS NOT NULL AND ITMCODE <> ''";
        
        $params = [];
        
        if ($search) {
            $sql .= " AND (ITMCODE LIKE ? 
                      OR ITMNME LIKE ? 
                      OR ITMMOD LIKE ? 
                      OR BRAND LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }

        $sql .= " ORDER BY ITMCODE";
        $sql .= " LIMIT {$limit} OFFSET {$offset}";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Count total items matching search criteria
     * 
     * @param string|null $search Search term
     * @return int Total count
     */
    public function countItems(?string $search = null): int
    {
        $pdo = $this->getConnection();

        $sql = "SELECT COUNT(*) as total FROM YBIMMST WHERE ITMCODE IS NOT NULL AND ITMCODE <> ''";
        
        $params = [];
        
        if ($search) {
            $sql .= " AND (ITMCODE LIKE ? 
                      OR ITMNME LIKE ? 
                      OR ITMMOD LIKE ? 
                      OR BRAND LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (int) $result['TOTAL'];
    }

    /**
     * Get a single item by ITMCODE
     * 
     * @param string $itmcode The item code
     * @return array|null The item or null if not found
     */
    public function getItem(string $itmcode): ?array
    {
        $pdo = $this->getConnection();

        $sql = "SELECT ITMCODE, ITMNME, ITMMOD, ITMAMP, F_WAR, PA_WAR, REMARK, PRPHASE, BRAND 
                FROM YBIMMST 
                WHERE ITMCODE = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$itmcode]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Search items in OITM table
     *
     * @param string|null $search Search term
     * @param int $limit Number of records to return
     * @return array List of items
     */
    public function searchOitmItems(?string $search = null, int $limit = 20): array
    {
        $pdo = $this->getConnection();

        $sql = "SELECT \"ItemCode\", \"ItemName\" FROM \"OITM\" WHERE \"ItemCode\" IS NOT NULL AND \"ItemCode\" <> ''";
        $params = [];

        if ($search) {
            $sql .= " AND (\"ItemCode\" LIKE ? OR \"ItemName\" LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm];
        }

        $sql .= " ORDER BY \"ItemCode\"";
        $sql .= " LIMIT {$limit}";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Get a single item by ItemCode from OITM
     *
     * @param string $itmcode The item code
     * @return array|null The item or null if not found
     */
    public function getOitmItem(string $itmcode): ?array
    {
        $pdo = $this->getConnection();

        $sql = "SELECT \"ItemCode\", \"ItemName\" FROM \"OITM\" WHERE \"ItemCode\" = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$itmcode]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Search warehouses in OWHS table
     *
     * @param string|null $search Search term
     * @param int $limit Number of records to return
     * @return array List of warehouses
     */
    public function searchWarehouses(?string $search = null, int $limit = 20): array
    {
        $pdo = $this->getConnection();

        $sql = "SELECT \"WhsCode\", \"WhsName\" FROM \"OWHS\" WHERE \"WhsCode\" IS NOT NULL AND \"WhsCode\" <> ''";
        $params = [];

        if ($search) {
            $sql .= " AND (\"WhsCode\" LIKE ? OR \"WhsName\" LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm];
        }

        $sql .= " ORDER BY \"WhsCode\"";
        $sql .= " LIMIT {$limit}";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Test connection to HANA
     * 
     * @return bool True if connection successful
     */
    public function testConnection(): bool
    {
        try {
            $this->getConnection();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
