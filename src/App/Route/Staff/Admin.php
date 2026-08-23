<?php
    declare(strict_types=1);

    namespace App\Route\Staff;

    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class Admin
    {
        public function registerAdminRoutes($app)
        {
            $app->get('/dashboard', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
        
                return $view->render($response, 'dashboard.html.twig', [
                    'name' => 'John',
                ]);
            })->add(new \App\Middleware\RequireStaffLogin());;
            
            $app->get('/food', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
        
                return $view->render($response, 'food.html.twig', [
                    'name' => 'John',
                ]);
            });

            $loginRoute = new \App\Route\Staff\Login();
            $loginRoute->RegisterLoginRoutes($app);

            $loginRoute = new \App\Route\Staff\Staff();
            $loginRoute->RegisterStaffRoutes($app);
        }
    }
?>