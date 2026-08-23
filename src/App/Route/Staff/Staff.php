<?php
    declare(strict_types=1);

    namespace App\Route\Staff;

    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class Staff
    {
        public function registerStaffRoutes($app)
        {
            $app->get('/add-staff', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
        
                return $view->render($response, 'staff-add.html.twig', [
                    'name' => 'John',
                ]);
            });

            $app->post('/add-staff', function ($request, $response, $args) {
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
        }
    }
?>