<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Factory\AppFactory;

    require __DIR__ . '/../vendor/autoload.php';

    $app = AppFactory::create();

    $foodRoute = new \App\Route\API\FoodRoute();
    $foodRoute->registerFoodRoutes($app);
    
    $customerRoute = new \App\Route\API\CustomerRoute();
    $customerRoute->registerCustomerRoutes($app);

    $categoryRoute = new \App\Route\API\CategoryRoute();
    $categoryRoute->registerCategoryRoutes($app);

    $cartRoute = new \App\Route\API\CartRoute();
    $cartRoute->registerCartRoutes($app);

    $userRoleRoute = new \App\Route\API\UserRoleRoute();
    $userRoleRoute->registerUserRoleRoutes($app);

    $userRoute = new \App\Route\API\UserRoute();
    $userRoute->registerUserRoutes($app);

    $foodRoute = new \App\Route\API\FoodRoute();
    $foodRoute->registerFoodRoutes($app);
    
    $app->run();
?>