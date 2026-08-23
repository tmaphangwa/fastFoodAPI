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

        public function getById(string $id):array{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        }

        public function getByEmail(string $email){
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            return $stmt->fetch();
        }

        public function create(string $email, string $password):array{
            try {
                $id = uniqid('', true);
                $passCode = bin2hex(random_bytes(3));
                
                $pdo = $this->database->getConnection();

                $stmt = $pdo->prepare('INSERT INTO users (id, email, password, passCode) VALUES (:id, :email, :password, :passCode)');
                $stmt->execute(['id' => $id, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT), 'passCode' => $passCode]);
                return $this->getById($id);
            } catch (PDOException $e) {
                $response = new \Slim\Psr7\Response();
                $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT));
                return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
            }
            
        }

        public function update(string $id, string $email, string $password):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('UPDATE users SET email = :email, password = :password WHERE id = :id');
            $stmt->execute(['id' => $id, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)]);
            return (bool)$stmt->rowCount();
        }

        public function delete(string $id):bool{
            $pdo = $this->database->getConnection();

            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return (bool)$stmt->rowCount();
        }
    }
?>