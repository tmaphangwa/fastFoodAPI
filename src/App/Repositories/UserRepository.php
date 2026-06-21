<?php
    declare(strict_types=1);
    namespace App\Repositories;
    use App\Database;
    use PDO;

    class UserRepository{
        public function __construct(private Database $database){

        }

        public function getAll():array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->query('SELECT * FROM users');
            return $stmt->fetchAll();
        }

        public function getById(int $id):array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        }

        public function create(string $email, string $password):int{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
            $stmt->execute(['email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)]);
            return (int)$pdo->lastInsertId();
        }

        public function update(int $id, string $email, string $password):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('UPDATE users SET email = :email, password = :password WHERE id = :id');
            $stmt->execute(['id' => $id, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)]);
            return (bool)$stmt->rowCount();
        }

        public function delete(int $id):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return (bool)$stmt->rowCount();
        }
    }
?>