<?php
    declare(strict_types=1);
    namespace App\Repositories;
    use App\Database;
    use PDO;

    class RoleRepository{
        public function __construct(private Database $database){

        }

        public function getAll():array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->query('SELECT * FROM roles');
            return $stmt->fetchAll();
        }

        public function getById(int $id):array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('SELECT * FROM roles WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        }

        public function create(string $name):int{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('INSERT INTO roles (name) VALUES (:name)');
            $stmt->execute(['name' => $name]);
            return (int)$pdo->lastInsertId();
        }

        public function update(int $id, string $name):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('UPDATE roles SET name = :name WHERE id = :id');
            $stmt->execute(['id' => $id, 'name' => $name]);
            return (bool)$stmt->rowCount();
        }

        public function delete(int $id):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('DELETE FROM roles WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return (bool)$stmt->rowCount();
        }
    }
?>