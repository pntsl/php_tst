<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use MiladRahimi\PhpRouter\Routing\Route;
use Laminas\Diactoros\Response\JsonResponse;

use Sunspikes\Ratelimit\RateLimiter;
use Sunspikes\Ratelimit\Cache\Adapter\DesarrollaCacheAdapter;
use Sunspikes\Ratelimit\Cache\Factory\DesarrollaCacheFactory;
use Sunspikes\Ratelimit\Throttle\Factory\ThrottlerFactory;
use Sunspikes\Ratelimit\Throttle\Hydrator\HydratorFactory;
use Sunspikes\Ratelimit\Throttle\Settings\ElasticWindowSettings;

use Common\Helper\ControllerHelper;


class ThrottleMiddleware
{
    public function handle(ServerRequestInterface $request, Route $route, \Closure $next)
    {
        $cacheAdapter = new DesarrollaCacheAdapter((new DesarrollaCacheFactory(
            null,
            [
                'driver' => 'file',
            ],
        ))->make());
        $settings = new ElasticWindowSettings(10, 60);
        $ratelimiter = new RateLimiter(new ThrottlerFactory($cacheAdapter), new HydratorFactory(), $settings);
        
        $throttler = $ratelimiter->get('/');
        if ($throttler->access()) {

            return $next($request);
        }

        [
            $response,
            $statusCode,
        ]
            = ControllerHelper::getErrorResponse([
                'Too much requests!'
            ], 503);
        return new JsonResponse($response, $statusCode);
    }
}
