<?php
    declare(strict_types=1);
    namespace App\Controllers;

    class DashboardController
    {
        public function index($request, $response, $args)
        {
            $response->getBody()->write("Welcome to the Dashboard");
            return $response;
        }
    }
?>