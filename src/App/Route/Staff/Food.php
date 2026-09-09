<?php
    declare(strict_types=1);
    namespace App\Route\Staff;

    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class Food
    {
        public function registerFoodRoutes($app)
        {
            $app->get('/food', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;

                $foodRepository = new \App\Repositories\FoodRepository($database);
                $foods = $foodRepository->findAll();
        
                return $view->render($response, 'food.html.twig', [
                    'foods' => $foods
                ]);
            });

            $app->get('/food/{id}', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;

                $foodRepository = new \App\Repositories\FoodRepository($database);
                $food = $foodRepository->findById((int)$args['id']);

                if (!$food) {
                    return $view->render($response, 'food.html.twig', [
                        'id' => $args['id']
                    ]);
                }

                return $view->render($response, 'food-view.html.twig', [
                    'food' => $food
                ]);
            });

            $app->post('/food', function ($request, $response, $args) {
                $database = new \App\Database;
                $foodRepository = new \App\Repositories\FoodRepository($database);

                $view = Twig::fromRequest($request);

                $data = $request->getParsedBody();

                if(isset($data['name'], $data['price'], $data['category_id'])){
                    $food = $foodRepository->create($data['name'], (float)$data['price'], (int)$data['category_id']);

                    if($food){
                        return $view->render($response, 'food-view.html.twig', [
                            'name' => $data['name'],
                            'price' => $data['price'],
                            'category_id' => $data['category_id']
                        ]);
                    } else {
                        return $view->render($response, 'food-add.html.twig', [
                            'error' => 'Failed to add food item.'
                        ]);
                    }
                } else {
                    return $view->render($response, 'food-add.html.twig', [
                        'error' => 'Missing required fields.'
                    ]);
                }
            });
        }
    }
?>