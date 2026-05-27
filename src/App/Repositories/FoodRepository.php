<?php
    declare(strict_types=1);

    namespace App\Repositories;

    use App\Database;
    use PDO;

    class FoodRepository{
        public function __construct(private Database $database){

        }

        public function getAll():array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->query('SELECT * FROM foods');
            return $stmt->fetchAll();
        }
    }
?>