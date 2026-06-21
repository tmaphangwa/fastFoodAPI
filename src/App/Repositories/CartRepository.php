<?php
    declare(strict_types=1);
    namespace App\Repositories;
    use App\Database;
    use PDO;

    class CartRepository{
        public function __construct(private Database $database){

        }

        public function getAll():array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->query('SELECT * FROM carts');
            return $stmt->fetchAll();
        }

        public function getById(string $id):array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('SELECT * FROM carts WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        }

        public function create(string $cartId, string $foodId, int $quantity):int{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('INSERT INTO carts (id) VALUES (:id)');
            $stmt->execute(['id' => $cartId]);
            return (int)$pdo->lastInsertId();
        }

        public function update(string $id, string $cartId, string $foodId, int $quantity):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('UPDATE carts SET id = :id WHERE id = :old_id');
            $stmt->execute(['id' => $cartId, 'old_id' => $id]);
            return (bool)$stmt->rowCount();
        }

        public function delete(string $id):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('DELETE FROM carts WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return (bool)$stmt->rowCount();
        }
    }
?>