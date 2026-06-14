<?php
    declare(strict_types=1);
    namespace App\Route\API;

    class UserRoute
    {
        public function registerUserRoutes($app)
        {
            $app->get('/api/users', function ($request, $response, $args) {
                $database = new \App\Database;

                $userRepository = new \App\Repositories\UserRepository($database);
                $data = $userRepository->getAll();

                $body = json_encode($data, JSON_PRETTY_PRINT);
                $response->getBody()->write($body);
                return $response->withHeader('Content-Type', 'application/json');
            });

            $app->get('/api/users/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $userRepository = new \App\Repositories\UserRepository($database);
                $data = $userRepository->getById((int)$args['id']);

                if ($data) {
                    $body = json_encode($data, true);
                    $response->getBody()->write($body);
                    return $response->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'User not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->post('/api/users', function ($request, $response, $args) {
                $database = new \App\Database;

                $userRepository = new \App\Repositories\UserRepository($database);
                $data = json_decode($request->getBody()->getContents(), true);

                if (isset($data['username']) && isset($data['email'])) {
                    $id = $userRepository->create($data['username'], $data['email']);
                    $response->getBody()->write(json_encode(['id' => $id], true));
                    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Invalid input'], true));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->put('/api/users/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $userRepository = new \App\Repositories\UserRepository($database);
                $data = json_decode($request->getBody()->getContents(), true);

                if (isset($data['username']) && isset($data['email'])) {
                    $updated = $userRepository->update((int)$args['id'], $data['username'], $data['email']);
                    if ($updated) {
                        return $response->withStatus(204);
                    } else {
                        $response->getBody()->write(json_encode(['error' => 'User not found'], true));
                        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                    }
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Invalid input'], true));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->delete('/api/users/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $userRepository = new \App\Repositories\UserRepository($database);
                $deleted = $userRepository->delete((int)$args['id']);

                if ($deleted) {
                    return $response->withStatus(204);
                } else {
                    $response->getBody()->write(json_encode(['error' => 'User not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });
        }
    }
?>