<?php
    declare(strict_types=1);
    namespace App\Route\Staff;

    use Slim\Views\Twig;
    use Slim\Views\TwigMiddleware;

    class Category
    {
        public function registerCategoryRoutes($app)
        {
            $app->get('/category', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;

                $categoryRepository = new \App\Repositories\CategoryRepository($database);
                $categories = $categoryRepository->findAll();

                return $view->render($response, 'category.html.twig', [
                    'categories' => $categories
                ]);
            });

            $app->get('/category/{id}', function ($request, $response, $args) {
                $view = Twig::fromRequest($request);
                $database = new \App\Database;

                $categoryRepository = new \App\Repositories\CategoryRepository($database);
                $category = $categoryRepository->findById((int)$args['id']);

                if (!$category) {
                    return $view->render($response, 'category.html.twig', [
                        'id' => $args['id']
                    ]);
                }

                return $view->render($response, 'category-view.html.twig', [
                    'category' => $category
                ]);
            });

            $app->post('/category', function ($request, $response, $args) {
                $database = new \App\Database;
                $categoryRepository = new \App\Repositories\CategoryRepository($database);

                $view = Twig::fromRequest($request);

                $data = $request->getParsedBody();

                if(isset($data['name'])){
                    $category = $categoryRepository->create($data['name']);

                    if($category){
                        return $view->render($response, 'category-view.html.twig', [
                            'name' => $data['name']
                        ]);
                    } else {
                        return $view->render($response, 'category-add.html.twig', [
                            'error' => 'Failed to add category.'
                        ]);
                    }
                }
            });
        }
    }
?>