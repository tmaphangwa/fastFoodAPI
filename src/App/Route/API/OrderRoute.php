<?php
    declare(strict_types=1);
    namespace App\Route\API;

    class OrderRoute
    {
        public function registerOrderRoutes($app)
        {
            $app->get('/api/orders', function ($request, $response, $args) {
                $database = new \App\Database;

                $orderRepository = new \App\Repositories\OrderRepository($database);
                $data = $orderRepository->getAll();

                $body = json_encode($data, JSON_PRETTY_PRINT);
                $response->getBody()->write($body);
                return $response->withHeader('Content-Type', 'application/json');
            });

            $app->get('/api/orders/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $orderRepository = new \App\Repositories\OrderRepository($database);
                $data = $orderRepository->getById((int)$args['id']);

                if ($data) {
                    $body = json_encode($data, true);
                    $response->getBody()->write($body);
                    return $response->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Order not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->post('/api/orders', function ($request, $response, $args) {
                $database = new \App\Database;

                $orderRepository = new \App\Repositories\OrderRepository($database);
                $data = json_decode($request->getBody(), true);
                $newOrderId = $orderRepository->create($data);

                if ($newOrderId) {
                    $response->getBody()->write(json_encode(['id' => $newOrderId], true));
                    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Failed to create order'], true));
                    return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->put('/api/orders/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $orderRepository = new \App\Repositories\OrderRepository($database);
                $data = json_decode($request->getBody(), true);
                $updated = $orderRepository->update((int)$args['id'], $data);

                if ($updated) {
                    return $response->withStatus(204);
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Failed to update order'], true));
                    return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
                }
            });

            $app->delete('/api/orders/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $orderRepository = new \App\Repositories\OrderRepository($database);
                $deleted = $orderRepository->delete((int)$args['id']);

                if ($deleted) {
                    return $response->withStatus(204);
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Failed to delete order'], true));
                    return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
                }
            });
        }
    }
?>