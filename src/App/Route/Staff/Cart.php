<?php
    declare(strict_types=1);
    namespace App\Route\Staff;

    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class Cart
    {
        public function registerCartRoutes($app)
        {
            $app->get('/cart', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;

                $cartRepository = new \App\Repositories\CartRepository($database);
                $carts = $cartRepository->findAll();
        
                return $view->render($response, 'cart.html.twig', [
                    'carts' => $carts
                ]);
            });

            $app->get('/cart/{id}', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;

                $cartRepository = new \App\Repositories\CartRepository($database);
                $cart = $cartRepository->findById((int)$args['id']);

                if (!$cart) {
                    return $view->render($response, 'cart.html.twig', [
                        'id' => $args['id']
                    ]);
                }

                return $view->render($response, 'cart-view.html.twig', [
                    'cart' => $cart
                ]);
            });

            $app->post('/cart', function ($request, $response, $args) {
                $database = new \App\Database;
                $cartRepository = new \App\Repositories\CartRepository($database);

                $view = Twig::fromRequest($request);

                $data = $request->getParsedBody();

                if(isset($data['user_id'], $data['food_id'], $data['quantity'])){
                    $cart = $cartRepository->create($data['user_id'], $data['food_id'], (int)$data['quantity']);

                    if($cart){
                        return $view->render($response, 'cart-view.html.twig', [
                            'user_id' => $data['user_id'],
                            'food_id' => $data['food_id'],
                            'quantity' => $data['quantity']
                        ]);
                    } else {
                        return $view->render($response, 'cart-add.html.twig', [
                            'error' => 'Failed to add item to cart.'
                        ]);
                    }
                } else {
                    return $view->render($response, 'cart-add.html.twig', [
                        'error' => 'Missing required fields.'
                    ]);
                }
            });
        }
    }
?>