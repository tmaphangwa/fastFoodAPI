<?php
    declare(strict_types=1);

    namespace App\Route\Staff;

    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class Staff
    {
        public function registerStaffRoutes($app)
        {
            $app->get('/staff', function ($request, $response, $args) {
                $database = new \App\Database;
                $userRepository = new \App\Repositories\Staff($database);
                $staffMembers = $userRepository->findAll();

                $view = Twig::fromRequest($request);
        
                return $view->render($response, 'staff.html.twig', [
                    'name' => 'John',
                    'staffMembers' => $staffMembers
                ]);
            });
            $app->get('/staff-add', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
        
                return $view->render($response, 'staff-add.html.twig', [
                    'name' => 'John',
                ]);
            });

            $app->post('/staff-add', function ($request, $response, $args) {
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

            $app->get('/staff-view/{id}', function ($request, $response, $args) {
                $database = new \App\Database;
                $userRepository = new \App\Repositories\Staff($database);
                $staffMember = $userRepository->findById((int)$args['id']);

                if ($staffMember) {
                    $view = Twig::fromRequest($request);
                    return $view->render($response, 'staff-view.html.twig', [
                        'name' => $staffMember['name'],
                        'surname' => $staffMember['surname'],
                        'email' => $staffMember['email']
                    ]);
                } else {
                    return $response->withStatus(404)->write('Staff member not found');
                }
            });

            $app->put('/staff-update/{id}', function ($request, $response, $args) {
                $database = new \App\Database;
                $userRepository = new \App\Repositories\Staff($database);
                $staffMember = $userRepository->findById((int)$args['id']);

                if ($staffMember) {
                    $data = $request->getParsedBody();
                    if(isset($data['name'], $data['surname'], $data['email'])){
                        $updatedStaffMember = $userRepository->update((int)$args['id'], $data['name'], $data['surname'], $data['email']);
                        if($updatedStaffMember){
                            return $response->withStatus(200)->write('Staff member updated successfully');
                        } else {
                            return $response->withStatus(500)->write('Failed to update staff member');
                        }
                    } else {
                        return $response->withStatus(400)->write('Invalid input data');
                    }
                } else {
                    return $response->withStatus(404)->write('Staff member not found');
                }
            });
        }
    }
?>