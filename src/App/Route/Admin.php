<?php
    declare(strict_types=1);
    namespace App\Route\Admin;

    class Admin
    {
        public function registerAdminRoutes($app)
        {
            $app->get('/dashboard', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
        
                return $view->render($response, 'dashboard.html.twig', [
                    'name' => 'John',
                ]);
            });
            
            $app->get('/food', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
        
                return $view->render($response, 'food.html.twig', [
                    'name' => 'John',
                ]);
            });
        }
    }
?>