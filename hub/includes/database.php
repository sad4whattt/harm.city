<?php
/**
 * harm.city Monitoring - MySQL connection
 */
class Database {
    private string $host     = 'localhost';
    private string $db_name  = 'harmcity_monitor';
    private string $username = 'root';
    private string $password = '';

    private ?\PDO $conn  = null;
    private ?string $error = null;

    /**
     * Establish a secure PDO connection
     * @return \PDO|false
     */
    public function connect() {
        if ($this->conn !== null) {
            return $this->conn;
        }

        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->conn = new \PDO($dsn, $this->username, $this->password, $options);
            return $this->conn;
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            error_log('[DB] ' . $this->error);
            return false;
        }
    }

    /**
     * Execute a parameterised query safely
     * @param string $sql  SQL string with placeholders
     * @param array  $params  Values to bind
     * @return \PDOStatement|false
     */
    public function query(string $sql, array $params = []) {
        if (!$this->connect()) {
            return false;
        }
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            error_log('[DB] ' . $this->error);
            return false;
        }
    }

    public function lastInsertId(): string {
        return $this->conn?->lastInsertId() ?? '0';
    }
    
    public function getError(): ?string {
        return $this->error;
    }
}
?>