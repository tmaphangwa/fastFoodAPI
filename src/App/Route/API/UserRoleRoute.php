<?php
    declare(strict_types=1);
    namespace App\Route\API;

    class UserRoleRoute
    {
        public function registerUserRoleRoutes($app)
        {
            $app->get('/api/user-roles', function ($request, $response, $args) {
                $database = new \App\Database;

                $userRoleRepository = new \App\Repositories\UserRoleRepository($database);
                $data = $userRoleRepository->getAll();

                $body = json_encode($data, JSON_PRETTY_PRINT);
                $response->getBody()->write($body);
                return $response->withHeader('Content-Type', 'application/json');
            });

            $app->get('/api/user-roles/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $userRoleRepository = new \App\Repositories\UserRoleRepository($database);
                $data = $userRoleRepository->getById((int)$args['id']);

                if ($data) {
                    $body = json_encode($data, true);
                    $response->getBody()->write($body);
                    return $response->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'User role not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->post('/api/user-roles', function ($request, $response, $args) {
                $database = new \App\Database;

                $userRoleRepository = new \App\Repositories\UserRoleRepository($database);
                $data = json_decode($request->getBody()->getContents(), true);

                if (isset($data['name'])) {
                    $newUserRoleId = $userRoleRepository->create($data['name']);
                    $response->getBody()->write(json_encode(['id' => $newUserRoleId], true));
                    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Invalid input'], true));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->put('/api/user-roles/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $userRoleRepository = new \App\Repositories\UserRoleRepository($database);
                $data = json_decode($request->getBody()->getContents(), true);

                if (isset($data['name'])) {
                    $updated = $userRoleRepository->update((int)$args['id'], $data['name']);
                    if ($updated) {
                        return $response->withStatus(204);
                    } else {
                        $response->getBody()->write(json_encode(['error' => 'User role not found'], true));
                        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                    }
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Invalid input'], true));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->delete('/api/user-roles/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $userRoleRepository = new \App\Repositories\UserRoleRepository($database);
                $deleted = $userRoleRepository->delete((int)$args['id']);

                if ($deleted) {
                    return $response->withStatus(204);
                } else {
                    $response->getBody()->write(json_encode(['error' => 'User role not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });
        }
    }
?>