<?php
    declare(strict_types=1);
    namespace App\Route\Staff;

    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class Category
    {
        public function registerCategoryRoutes($app)
        {
            $app->get('/customer', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;
                $userRepository = new \App\Repositories\CustomerRepository($database);

                $customers = $userRepository->findAll();
        
                return $view->render($response, 'customer.html.twig', [
                    'customers' => $customers
                ]);
            });

            $app->get('/customer/{id}', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;

                $userRepository = new \App\Repositories\CustomerRepository($database);
                $customer = $userRepository->findById((int)$args['id']);

                if (!$customer) {
                    return $view->render($response, 'customer.html.twig', [
                        'id' => $args['id']
                    ]);
                }

                return $view->render($response, 'customer-view.html.twig', [
                    'customer' => $customer
                ]);
            });
        }
    }
?>