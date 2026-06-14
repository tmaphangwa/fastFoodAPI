<?php
    declare(strict_types=1);
    namespace App\Route\API;

    class CustomerRoute
    {
        public function registerCustomerRoutes($app)
        {
            $app->get('/api/customers', function ($request, $response, $args) {
                $database = new \App\Database;

                $customerRepository = new \App\Repositories\CustomerRepository($database);
                $data = $customerRepository->getAll();

                $body = json_encode($data, JSON_PRETTY_PRINT);
                $response->getBody()->write($body);
                return $response->withHeader('Content-Type', 'application/json');
            });

            $app->get('/api/customers/{id}', function ($request, $response, $args) {
                $database = new \App\Database;

                $customerRepository = new \App\Repositories\CustomerRepository($database);
                $data = $customerRepository->getById((int)$args['id']);

                if ($data) {
                    $body = json_encode($data, true);
                    $response->getBody()->write($body);
                    return $response->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode(['error' => 'Customer not found'], true));
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            });

            // Additional routes for POST, PUT, DELETE can be added similarly
        }
    }
?>