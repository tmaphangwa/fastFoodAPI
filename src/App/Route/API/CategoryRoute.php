<?php
    declare(strict_types=1);
    namespace App\Route\API;

    class CategoryRoute
    {
        public function registerCategoryRoutes($app)
        {
            $app->get('/api/categories', function ($request, $response, $args) {
                $database = new \App\Database;

                $categoryRepository = new \App\Repositories\CategoryRepository($database);
                $data = $categoryRepository->getAll();

                $body = json_encode($data, JSON_PRETTY_PRINT);
                $response->getBody()->write($body);
                return $response->withHeader('Content-Type', 'application/json');
            });

            $app->get('/api/categories/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $categoryRepository = new \App\Repositories\CategoryRepository($database);
                $data = $categoryRepository->getById((int)$args['id']);

                if ($data) {
                    $body = json_encode($data, true);
                    $response->getBody()->write($body);
                    return $response->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Category not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->post('/api/categories', function ($request, $response, $args) {
                $database = new \App\Database;

                $categoryRepository = new \App\Repositories\CategoryRepository($database);
                $data = json_decode($request->getBody()->getContents(), true);

                if (isset($data['name'])) {
                    $newCategoryId = $categoryRepository->create($data['name'], $data['description'] ?? '');
                    $response->getBody()->write(json_encode(['id' => $newCategoryId], true));
                    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Invalid input'], true));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->put('/api/categories/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $categoryRepository = new \App\Repositories\CategoryRepository($database);
                $data = json_decode($request->getBody()->getContents(), true);

                if (isset($data['name'])) {
                    $updated = $categoryRepository->update((int)$args['id'], $data['name'], $data['description'] ?? '');
                    if ($updated) {
                        return $response->withStatus(204);
                    } else {
                        $response->getBody()->write(json_encode(['error' => 'Category not found'], true));
                        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                    }
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Invalid input'], true));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->delete('/api/categories/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $categoryRepository = new \App\Repositories\CategoryRepository($database);
                $deleted = $categoryRepository->delete((int)$args['id']);

                if ($deleted) {
                    return $response->withStatus(204);
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Category not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });
        }
    }
?>