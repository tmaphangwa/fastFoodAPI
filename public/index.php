<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Factory\AppFactory;
    use Dotenv\Dotenv;

    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $app = AppFactory::create();

    $twig = Twig::create(__DIR__ . '/../views', ['cache' => false]);
    $app->add(TwigMiddleware::create($app, $twig));

    $app->get('/', function ($request, $response) {
        $view = Twig::fromRequest($request);
        
        return $view->render($response, 'dashboard.html.twig', [
            'name' => 'John',
        ]);
    });

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
    
    $app->run();
?>