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

        public function create(string $name, string $description, int $categoryId, float $price):array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('INSERT INTO foods (id, name, description, categoryId, price) VALUES (:id, :name, :description, :categoryId, :price)');
            $stmt->execute(['id' => null, 'name' => $name, 'description' => $description, 'categoryId' => $categoryId, 'price' => $price]);
            return (int)$pdo->lastInsertId();
        }

        public function update( $id, string $name, string $description, $categoryId, float $price):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('UPDATE foods SET name = :name, description = :description, categoryId = :categoryId, price = :price WHERE id = :id');
            $stmt->execute(['id' => $id, 'name' => $name, 'description' => $description, 'categoryId' => $categoryId, 'price' => $price]);
            return (bool)$stmt->rowCount();
        }

        public function delete($id):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('DELETE FROM foods WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return (bool)$stmt->rowCount();
        }
    }
?>