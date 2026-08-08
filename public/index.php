<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Factory\AppFactory;
    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;
    use Dotenv\Dotenv;

    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $app = AppFactory::create();

    $twig = Twig::create(__DIR__ . '/../views', ['cache' => false]);
    $app->add(TwigMiddleware::create($app, $twig));

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

    $adminRoute = new \App\Route\Staff\Admin();
    $adminRoute->registerAdminRoutes($app);
    
    $app->run();
?>