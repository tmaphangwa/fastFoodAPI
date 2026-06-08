<?php
    Declare(strict_types=1);
    namespace App\Route;

    class Api
    {
        public function registerFoodRoutes($app)
        {
            $app->get('/api/foods', function ($request, $response, $args) {
                $database = new \App\Database;

                $foodRepository = new \App\Repositories\FoodRepository($database);
                $data = $foodRepository->getAll();

                $body = json_encode($data, JSON_PRETTY_PRINT);
                $response->getBody()->write($body);
                return $response->withHeader('Content-Type', 'application/json');
            });

            $app->get('/api/foods/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $foodRepository = new \App\Repositories\FoodRepository($database);
                $data = $foodRepository->getById((int)$args['id']);

                if ($data) {
                    $body = json_encode($data, true);
                    $response->getBody()->write($body);
                    return $response->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Food not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->post('/api/foods', function ($request, $response, $args) {
                $database = new \App\Database;

                $foodRepository = new \App\Repositories\FoodRepository($database);
                $data = json_decode($request->getBody()->getContents(), true);

                if (isset($data['name']) && isset($data['price'])) {
                    $id = $foodRepository->create($data['name'], (float)$data['price']);
                    $response->getBody()->write(json_encode(['id' => $id], true));
                    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Invalid input'], true));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->put('/api/foods/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $foodRepository = new \App\Repositories\FoodRepository($database);
                $data = json_decode($request->getBody()->getContents(), true);

                if (isset($data['name']) && isset($data['price'])) {
                    $updated = $foodRepository->update((int)$args['id'], $data['name'], (float)$data['price']);
                    if ($updated) {
                        return $response->withStatus(204);
                    } else {
                        $response->getBody()->write(json_encode(['error' => 'Food not found'], true));
                        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                    }
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Invalid input'], true));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->delete('/api/foods/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $foodRepository = new \App\Repositories\FoodRepository($database);
                $deleted = $foodRepository->delete((int)$args['id']);

                if ($deleted) {
                    return $response->withStatus(204);
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Food not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });
        }
    }
?>