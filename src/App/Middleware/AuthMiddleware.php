<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use MiladRahimi\PhpRouter\Routing\Route;
use Laminas\Diactoros\Response\JsonResponse;

use Common\Helper\ControllerHelper;

use Common\InterfacePool\ServicesInterface;


class AuthMiddleware
{
    public function handle(ServicesInterface $services, ServerRequestInterface $request, Route $route, \Closure $next)
    {
        $authService = $services->get('auth');

        if ($userToken = $request->getHeader('Auth-Token')) {

            $userType = $authService->getUserType(array_get($userToken, 0));
            if ($authService->isRouteAllowedForUserType($route->getName(), $userType)) {

                return $next($request);
            }
        }

        [
            $response,
            $statusCode,
        ]
            = ControllerHelper::getErrorResponse([
                'Request denied!'
            ], 403);
        return new JsonResponse($response, $statusCode);
    }
}
