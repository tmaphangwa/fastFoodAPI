<?php
    declare(strict_types=1);
    namespace App\Route\Staff;

    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class Order
    {
        public function registerOrderRoutes($app)
        {
            $app->get('/order', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;
                $orderRepository = new \App\Repositories\OrderRepository($database);

                $orders = $orderRepository->findAll();
        
                return $view->render($response, 'order.html.twig', [
                    'orders' => $orders
                ]);
            });

            $app->get('/order/{id}', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;

                $orderRepository = new \App\Repositories\OrderRepository($database);
                $order = $orderRepository->findById((int)$args['id']);

                if (!$order) {
                    return $view->render($response, 'order.html.twig', [
                        'id' => $args['id']
                    ]);
                }

                return $view->render($response, 'order-view.html.twig', [
                    'order' => $order
                ]);
            });
        }
    }
?>