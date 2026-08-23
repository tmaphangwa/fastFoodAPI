<?php
    declare(strict_types=1);
    namespace App\Repositories;
    use App\Database;

    class StaffRepository{
        public function __construct(private Database $database){

        }
        public function getAll(){
            $pdo = $this->database->getConnection();

            $stmt = $pdo->query('SELECT * FROM staff');
            return $stmt->fetchAll();
        }

        public function getById(int $id){
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('SELECT * FROM staff WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        }

        public function create(string $name, string $surname, string $userId){
            $id = uniqid('', true);
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('INSERT INTO staff (id, name, surname, userId) VALUES (:id, :name, :surname, :userId)');
            $stmt->execute(['id' => $id, 'name' => $name, 'surname' => $surname, 'userId' => $userId]);
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