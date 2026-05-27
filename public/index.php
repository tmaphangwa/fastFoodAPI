<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Factory\AppFactory;

    require __DIR__ . '/../vendor/autoload.php';

    $app = AppFactory::create();
    
    $app->get('/', function (Request $request, Response $response, $args) {
        $database = new App\Database;

        $foodRepository = new App\Repositories\FoodRepository($database);
        $data = $foodRepository->getAll();

        $body = json_encode($data, true);
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->run();
?>