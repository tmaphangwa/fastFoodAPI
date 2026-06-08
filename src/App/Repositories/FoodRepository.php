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

        public function getById(int $id):array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('SELECT * FROM foods WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        }

        public function create(string $name, float $price):int{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('INSERT INTO foods (name, price) VALUES (:name, :price)');
            $stmt->execute(['name' => $name, 'price' => $price]);
            return (int)$pdo->lastInsertId();
        }

        public function update(int $id, string $name, float $price):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('UPDATE foods SET name = :name, price = :price WHERE id = :id');
            $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price]);
            return (bool)$stmt->rowCount();
        }

        public function delete(int $id):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('DELETE FROM foods WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return (bool)$stmt->rowCount();
        }
    }
?>