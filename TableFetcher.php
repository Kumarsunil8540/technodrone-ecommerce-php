
<?php
// TableFetcher.php
if (!class_exists('TableFetcher')) {
    class TableFetcher {
        private $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

        private function validTableName(string $t): bool {
            return (bool) preg_match('/^[a-zA-Z0-9_]+$/', $t);
        }

        public function renderTable(string $table, array $columns): void {
            if (!$this->validTableName($table)) {
                echo "<tr><td colspan='".(count($columns)+1)."'>Invalid table name</td></tr>";
                return;
            }

            $sql = "SELECT * FROM `$table`";
            $stmt = $this->pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($columns as $col) {
                    $val = $row[$col] ?? '';
                    echo "<td>" . htmlspecialchars((string)$val, ENT_QUOTES) . "</td>";
                }

                $tableJs = addslashes($table);
                $id = isset($row['id']) ? (int)$row['id'] : 0;

                echo "<td>";
                echo "<button class='delete-btn' onclick=\"deleteRow('$tableJs',$id)\">Delete</button>";
                echo "</td>";
                echo "</tr>";
            }
        }

        public function fetchAll(string $table): array {
            if (!$this->validTableName($table)) return [];
            $stmt = $this->pdo->query("SELECT * FROM `$table`");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


    }
}


