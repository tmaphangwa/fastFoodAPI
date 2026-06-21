<?php
    declare(strict_types=1);

    namespace App\Middleware;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    
    class JwtToken{
        private string $secretKey = 'your_secret_key'; 

        public function generateToken(array $payload): string{
            return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
        }

        public function validateToken(string $token): ?array{
            try {
                $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
                return (array)$decoded;
            } catch (ExpiredException $e) {
                // Thrown if the current time is past the 'exp' timestamp
                $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT));
                return $response->withStatus(401);
            } catch (BeforeValidException $e) {
                // Thrown if the current time is before the 'nbf' timestamp
                $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT));
                return $response->withStatus(401);
            } catch (SignatureInvalidException $e) {
                // Thrown for invalid signatures
                $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT));
                return $response->withStatus(401);
            } catch (SignatureInvalidException $e) {
                // Thrown for invalid signatures
                $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT));
                return $response->withStatus(401);
            } catch (\Exception $e) {
                // Thrown for invalid signatures or malformed tokens
                $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT));
                return $response->withStatus(401);
            }
        }

        public function getTokenFromHeader(): ?string{
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $parts = explode(' ', $headers['Authorization']);
                if (count($parts) === 2 && $parts[0] === 'Bearer') {
                    return $parts[1];
                }
            }
            return null;
        }

        public function authenticate(): ?array{
            $token = $this->getTokenFromHeader();
            if ($token) {
                return $this->validateToken($token);
            }
            return null;
        }

    } 
?>