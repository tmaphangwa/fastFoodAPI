<?php
    declare(strict_types=1);

    namespace App\Middleware;

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

    class RequireToken{
        public function __invoke(Request $request, RequestHandler $handler): Response{
            $authHeader = $request->getHeaderLine('Authorization');

            $response = new \Slim\Psr7\Response();
            
            if (!$authHeader) {
                $response->getBody()->write(json_encode(['error' => 'Authorization header missing'], JSON_PRETTY_PRINT));
                return $response->withStatus(401);
            }

            $token = str_replace('Bearer ', '', $authHeader);
            try {
                $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
                // You can add user information to the request attributes if needed
                if (is_array($decoded)) {
                    $decoded = (object)$decoded; // Convert to object if it's an array
                }
                
                if (!isset($decoded->id, $decoded->role, $decoded->iat)) {
                    $response->getBody()->write(json_encode(['error' => 'Invalid token: missing required claims'], JSON_PRETTY_PRINT));
                    return $response->withStatus(401);
                }

                $userRepository = new \App\Repositories\UserRepository($database);
                $user = $userRepository->getById($decoded->id);

                if (!$user) {
                    $response->getBody()->write(json_encode(['error' => 'User not found'], JSON_PRETTY_PRINT));
                    return $response->withStatus(401);
                }

                if (intval($decoded->iat)+3600 < time()) { // Check if the token is expired (1 hour expiration)
                    $response->getBody()->write(json_encode(['error' => 'Token is expired'], JSON_PRETTY_PRINT));
                    return $response->withStatus(401);
                }

                $request = $request->withAttribute('user', $decoded);
                return $handler->handle($request);
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
    }
?>