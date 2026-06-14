<?php
    declare(strict_types=1);
    namespace App\Repositories;
    use App\Database;

    class CartItemRepository{
        public function __construct(private Database $database){

        }
        public function getAll():array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->query('SELECT * FROM cart_items');
            return $stmt->fetchAll();
        }

        public function getById(int $id):array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('SELECT * FROM cart_items WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        }

        public function create(int $cartId, int $foodId, int $quantity):int{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('INSERT INTO cart_items (cart_id, food_id, quantity) VALUES (:cart_id, :food_id, :quantity)');
            $stmt->execute(['cart_id' => $cartId, 'food_id' => $foodId, 'quantity' => $quantity]);
            return (int)$pdo->lastInsertId();
        }

        public function update(int $id, int $cartId, int $foodId, int $quantity):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('UPDATE cart_items SET cart_id = :cart_id, food_id = :food_id, quantity = :quantity WHERE id = :id');
            $stmt->execute(['id' => $id, 'cart_id' => $cartId, 'food_id' => $foodId, 'quantity' => $quantity]);
            return (bool)$stmt->rowCount();
        }

        public function delete(int $id):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('DELETE FROM cart_items WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return (bool)$stmt->rowCount();
        }
    }
?>