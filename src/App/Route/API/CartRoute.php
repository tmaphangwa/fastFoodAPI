<?php
    declare(strict_types=1);
    namespace App\Route\API;

    class CartRoute
    {
        public function registerCartRoutes($app)
        {
            $app->get('/api/carts', function ($request, $response, $args) {
                $database = new \App\Database;

                $cartRepository = new \App\Repositories\CartRepository($database);
                $data = $cartRepository->getAll();

                $body = json_encode($data, JSON_PRETTY_PRINT);
                $response->getBody()->write($body);
                return $response->withHeader('Content-Type', 'application/json');
            });

            $app->get('/api/carts/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $cartRepository = new \App\Repositories\CartRepository($database);
                $data = $cartRepository->getById((int)$args['id']);

                if ($data) {
                    $body = json_encode($data, true);
                    $response->getBody()->write($body);
                    return $response->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Cart not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->post('/api/carts', function ($request, $response, $args) {
                $database = new \App\Database;

                $cartRepository = new \App\Repositories\CartRepository($database);
                $data = json_decode($request->getBody()->getContents(), true);

                if (isset($data['name'])) {
                    $newCartId = $cartRepository->create($data['name']);
                    $response->getBody()->write(json_encode(['id' => $newCartId], true));
                    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Invalid input'], true));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->put('/api/carts/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $cartRepository = new \App\Repositories\CartRepository($database);
                $data = json_decode($request->getBody()->getContents(), true);

                if (isset($data['name'])) {
                    $updated = $cartRepository->update((int)$args['id'], $data['name']);
                    if ($updated) {
                        return $response->withStatus(204);
                    } else {
                        $response->getBody()->write(json_encode(['error' => 'Cart not found'], true));
                        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                    }
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Invalid input'], true));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->delete('/api/carts/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $cartRepository = new \App\Repositories\CartRepository($database);
                $deleted = $cartRepository->delete((int)$args['id']);

                if ($deleted) {
                    return $response->withStatus(204);
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Cart not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });
        }
    }
?>