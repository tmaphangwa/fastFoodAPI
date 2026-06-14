<?php
    declare(strict_types=1);
    namespace App\Repositories;
    use App\Database;
    use PDO;

    class CartItemRepository{
        public function __construct(private Database $database){

        }

        public function getAll():array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->query('SELECT * FROM categories');
            return $stmt->fetchAll();
        }

        public function getById(string $id):array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        }

        public function create(string $name, string $description):int{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)');
            $stmt->execute(['name' => $name, 'description' => $description]);
            return (int)$pdo->lastInsertId();
        }

        public function update(string $id, string $name, string $description):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('UPDATE categories SET name = :name, description = :description WHERE id = :id');
            $stmt->execute(['id' => $id, 'name' => $name, 'description' => $description]);
            return (bool)$stmt->rowCount();
        }

        public function delete(string $id):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return (bool)$stmt->rowCount();
        }
    }
?>