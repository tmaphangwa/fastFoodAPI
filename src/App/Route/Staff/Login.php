<?php
    declare(strict_types=1);

    namespace App\Route\Staff;

    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class Login
    {
        public function registerLoginRoutes($app)
        {
            $app->get('/login', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
        
                return $view->render($response, 'login.html.twig', [
                    'name' => 'John',
                ]);
            });

            $app->post('/login', function ($request, $response, $args) {
                $database = new \App\Database;
                $userRepository = new \App\Repositories\UserRepository($database);

                $view = Twig::fromRequest($request);

                $data = $request->getParsedBody();

                if (isset($data['email']) && isset($data['password'])) {
                    $user = $userRepository->getByEmail($data['email']);
                    if ($user && password_verify($data['password'], $user['password'])) {
                        $userRoleRepository = new \App\Repositories\UserRoleRepository($database);
                        $userRoles = $userRoleRepository->getById($user['id']);

                        session_start();$_SESSION['userId'] = $user['id'];
                        session_start();$_SESSION['name'] = $user['username'];

                        if(isset($data['page'])){
                            return $view->render($response, $dat['page'].'.html.twig', [
                                'name' => 'John',
                            ]);
                        }else{
                            return $view->render($response, 'dashboard.html.twig', [
                                'name' => 'John',
                            ]);
                        }

                    } else {
                        return $view->render($response, 'login.html.twig', [
                            'error' => 'Wrong User Cridentials',
                            'page' => $data['page']
                        ]);
                    }
                } else {
                    return $view->render($response, 'login.html.twig', [
                        'error' => 'You can not live the box empty',
                        'page' => $data['page']
                    ]);
                }
            });
        }
    }
?>