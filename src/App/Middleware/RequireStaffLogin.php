<?php
    declare(strict_types=1);

    namespace App\Middleware;

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class RequireStaffLogin{
        public function __invoke(Request $request, RequestHandler $handler): Response{
            if(!isset($_SESSION['userId'])){
                $response = new \Slim\Psr7\Response();
                $view = Twig::fromRequest($request);
        
                return $view->render($response, 'login.html.twig', [
                    'page' => $request->getUri()->getPath(),
                ]);
            }
            
            return $handler->handle($request);
        }
    }
?>