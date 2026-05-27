<?php
    Declare(strict_types=1);

    namespace App;

    use PDO;

    class Database {

        public function __construct() {
            $dsn = 'mysql:host=localhost;dbname=fastFood';
            $this->pdo = new PDO($dsn, 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        public function getConnection(): PDO {
            return $this->pdo;
        }
    }
?>