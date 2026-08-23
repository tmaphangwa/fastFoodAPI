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
        
                return $view->render($response, 'food.html.twig', [
                    'name' => 'John',
                ]);
            });

            $app->post('/food', function ($request, $response, $args) {
                $database = new \App\Database;
                $userRepository = new \App\Repositories\Staff($database);

                $view = Twig::fromRequest($request);

                $data = $request->getParsedBody();

                if(isset($data['name'], $data['surname'], $data['email'], $data['password'])){
                    $userRepository = new \App\Repositories\UserRepository($database);
                    $user = $userRepository->create($data['email'], $data['password']);

                    if($user){
                        $staffRepository = new \App\Repositories\Staff($database);
                        $staff = $staffRepository->create($data['name'], $data['surname'], $user['id']);

                        if($staff){
                            return $view->render($response, 'staff-view.html.twig', [
                                'name' => $data['name'],
                                'surname' => $data['surname'],
                                'email' => $data['email']
                            ]);
                        } else {
                            return $view->render($response, 'staff-add.html.twig', [
                                'name' => 'John',
                                'error' => 'Failed to add staff member.'
                            ]);
                        }

                        return $view->render($response, 'staff-view.html.twig', [
                            'name' => $data['name'],
                            'surname' => $data['surname'],
                            'email' => $data['email']
                        ]);
                    } else {
                        return $view->render($response, 'staff-add.html.twig', [
                            'name' => 'John',
                            'error' => 'Failed to add staff member.'
                        ]);
                    }
                }
            });

            $app->get('/food/{id}', function ($request, $response, $args) {
                $database = new \App\Database;
                $foodRepository = new \App\Repositories\FoodRepository($database);

                $foodId = (int)$args['id'];
                $food = $foodRepository->getById($foodId);

                $view = Twig::fromRequest($request);
                return $view->render($response, 'food-view.html.twig', [
                    'food' => $food
                ]);
            });

            $app->update('/food/{id}', function ($request, $response, $args) {
                $database = new \App\Database;
                $foodRepository = new \App\Repositories\FoodRepository($database);

                $foodId = (int)$args['id'];
                $data = $request->getParsedBody();

                if(isset($data['name'], $data['price'])){
                    $updated = $foodRepository->update($foodId, $data['name'], (float)$data['price']);

                    $view = Twig::fromRequest($request);

                    if($updated){
                        return $view->render($response, 'food-view.html.twig', [
                            'food' => $foodRepository->getById($foodId)
                        ]);
                    } else {
                        return $view->render($response, 'food-view.html.twig', [
                            'food' => $foodRepository->getById($foodId),
                            'error' => 'Failed to update food item.'
                        ]);
                    }
                }
            });

            $app->delete('/food/{id}', function ($request, $response, $args) {
                $database = new \App\Database;
                $foodRepository = new \App\Repositories\FoodRepository($database);

                $foodId = (int)$args['id'];
                $deleted = $foodRepository->delete($foodId);

                $view = Twig::fromRequest($request);

                if($deleted){
                    return $view->render($response, 'food-list.html.twig', [
                        'message' => 'Food item deleted successfully'
                    ]);
                } else {
                    return $view->render($response, 'food-list.html.twig', [
                        'error' => 'Failed to delete food item'
                    ]);
                }
            });
        }
    }
?>