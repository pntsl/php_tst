<?php
include_once('./vendor/autoload.php');

use MiladRahimi\PhpRouter\Router;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response\JsonResponse;
use function Functional\curry;

use Common\Helper\RouterHelper;

use App\Entity\User;
use App\Middleware\ThrottleMiddleware;
use App\Middleware\AuthMiddleware;
use App\Controller\OrderController;


[$router, $helpers, ] = include_once('./bootstrap.php');
$app = curry(function ($dispatch, $setup) use ($router) {

    $setup($router);
    $dispatch($router);
});

$app = $app(function ($router) {

    try {

        return $router->dispatch();
    } catch (\Exception) {
    }
});
$app(function ($router) use ($helpers) {

    $getPostContents = $helpers->offsetGet('getPostContents');
    $getServices = $helpers->offsetGet('getServices');

    $authService = $getServices()->get('auth');


    $authService->allowRouteForUserType('order_calc', User::TYPE_CUSTOMER);
    $router->group(['middleware' => [ThrottleMiddleware::class, AuthMiddleware::class, ]], function (Router $router) use ($getPostContents) {

        $router->post('/order/calc', function (OrderController $c, ServerRequest $request) use ($getPostContents) {

            $params = RouterHelper::jsonDecode($getPostContents($request));
            [
                $response,
                $statusCode,
            ]
                = $c->calc($params);

            return new JsonResponse($response, $statusCode);
        }, 'order_calc');
    });
    
    $authService->allowRouteForUserType('order_create', User::TYPE_CUSTOMER);
    $router->group(['middleware' => [ThrottleMiddleware::class, AuthMiddleware::class, ]], function (Router $router) use ($getPostContents) {

        $router->post('/order/create', function (OrderController $c, ServerRequest $request) use ($getPostContents) {

            $params = RouterHelper::jsonDecode($getPostContents($request));
            [
                $response,
                $statusCode,
            ]
                = $c->create($params);

            return new JsonResponse($response, $statusCode);
        }, 'order_create');
    });

    $authService->allowRouteForUserType('order_list', User::TYPE_OWNER);
    $router->group(['middleware' => [ThrottleMiddleware::class, AuthMiddleware::class, ]], function (Router $router) {

        $router->get('/order/list', function (OrderController $c) {

            [
                $response,
                $statusCode,
            ]
                = $c->list([]);

            return new JsonResponse($response, $statusCode);
        }, 'order_list');
    });
    
    $authService->allowRouteForUserType('order_view', User::TYPE_OWNER);
    $authService->allowRouteForUserType('order_view', User::TYPE_COURIER);
    $router->group(['middleware' => [ThrottleMiddleware::class, AuthMiddleware::class, ]], function (Router $router) {

        $router->get('/order/view/{id}', function ($id, OrderController $c) {

            [
                $response,
                $statusCode,
            ]
                = $c->view([
                    'order_id' => $id,
                ]);

            return new JsonResponse($response, $statusCode);
        }, 'order_view');
    });
});
