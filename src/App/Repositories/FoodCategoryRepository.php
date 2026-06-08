<?php
    declare(strict_types=1);
    namespace App\Repositories;
    use App\Database;
    use PDO;

    class FoodCategoryRepository{
        public function __construct(private Database $database){

        }

        public function getAll():array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->query('SELECT * FROM customers');
            return $stmt->fetchAll();
        }

        public function getById(int $id):array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('SELECT * FROM customers WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        }

        public function create(string $name, string $email):int{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('INSERT INTO customers (name, email) VALUES (:name, :email)');
            $stmt->execute(['name' => $name, 'email' => $email]);
            return (int)$pdo->lastInsertId();
        }

        public function update(int $id, string $name, string $email):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('UPDATE customers SET name = :name, email = :email WHERE id = :id');
            $stmt->execute(['id' => $id, 'name' => $name, 'email' => $email]);
            return (bool)$stmt->rowCount();
        }

        public function delete(int $id):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('DELETE FROM customers WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return (bool)$stmt->rowCount();
        }
    }
?>