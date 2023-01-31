<?php
namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthService
{
    protected array $routesForUserTypes = [];

    public function __construct(
        protected string|null $key = null
    ) {
    }
    

    public function getUserToken(string $userType): string
    {
        return JWT::encode(
            [
                'userType' => $userType,
            ],
            $this->key,
            'HS256'
        );
    }

    public function getUserType(string $userToken): string
    {
        $payload = JWT::decode($userToken, new Key($this->key, 'HS256'));
        return $payload->userType;
    }
    
    public function allowRouteForUserType($route, $userType): void
    {
        $routeKey = str_replace('/', '|', $route);
        $key = implode('/', [$routeKey, $userType, ]);
        array_set($this->routesForUserTypes, $key, null);
    }

    public function isRouteAllowedForUserType($route, $userType): bool
    {
        $routeKey = str_replace('/', '|', $route);
        $key = implode('/', [$routeKey, $userType, ]);
        return array_has($this->routesForUserTypes, $key);
    }
}
