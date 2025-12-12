<?php
class Counter {
    private $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Returns integer count of rows in given table
    public function getCount(string $tableName): int {
        // Basic whitelist/validation to avoid SQL injection via table name
        if (!preg_match('/^[a-z0-9_]+$/i', $tableName)) {
            throw new InvalidArgumentException("Invalid table name");
        }

        $sql = "SELECT COUNT(*) AS cnt FROM `$tableName`";
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['cnt'] ?? 0);
    }
}
