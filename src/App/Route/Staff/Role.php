<?php
    declare(strict_types=1);
    namespace App\Route\Staff;

    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class Role
    {
        public function registerRoleRoutes($app)
        {
            $app->get('/role', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;
                $roleRepository = new \App\Repositories\RoleRepository($database);

                $roles = $roleRepository->findAll();
        
                return $view->render($response, 'role.html.twig', [
                    'roles' => $roles
                ]);
            });

            $app->get('/role/{id}', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;

                $roleRepository = new \App\Repositories\RoleRepository($database);
                $role = $roleRepository->findById((int)$args['id']);

                if (!$role) {
                    return $view->render($response, 'role.html.twig', [
                        'id' => $args['id']
                    ]);
                }

                return $view->render($response, 'role-view.html.twig', [
                    'role' => $role
                ]);
            });

            $app->post('/role', function ($request, $response, $args) {
                $database = new \App\Database;
                $roleRepository = new \App\Repositories\RoleRepository($database);

                $view = Twig::fromRequest($request);

                $data = $request->getParsedBody();

                if(isset($data['name'])){
                    $role = $roleRepository->create($data['name']);

                    if($role){
                        return $view->render($response, 'role-view.html.twig', [
                            'name' => $data['name']
                        ]);
                    } else {
                        return $view->render($response, 'role-add.html.twig', [
                            'error' => 'Failed to add role.'
                        ]);
                    }
                } else {
                    return $view->render($response, 'role-add.html.twig', [
                        'error' => 'Name is required.'
                    ]);
                }
            });
        }
    }
?>