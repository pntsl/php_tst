<?php
use League\Config\Configuration;
use Nette\Schema\Expect;
use Guillermoandrae\Models\AbstractModel;
use Utopia\Registry\Registry;

use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\Router;

use Common\InterfacePool\ServicesInterface;
use Common\InterfacePool\ServicesWrapper;

use App\Service\AuthService;


$router = Router::create();
$appCfg = (function () {
        
    $cfg = new Configuration([
        'db' => Expect::structure([
            'host' => Expect::string()->default('localhost'),
            'name' => Expect::string()->required(),
            'user' => Expect::string()->required(),
            'pass' => Expect::string()->required(),
        ]),
        'auth' => Expect::structure([
            'key' => Expect::string()->required(),
        ]),
    ]);

    $envRaw = Dotenv\Dotenv::createImmutable(defined('ENV_DIR') ? ENV_DIR : __DIR__)->load();
    $env = new class($envRaw) extends AbstractModel {};
    $cfg->merge([
        'db' => [
            'host' => $env->offsetGet('DB_HOST'),
            'name' => $env->offsetGet('DB_NAME'),
            'user' => $env->offsetGet('DB_USER'),
            'pass' => $env->offsetGet('DB_PASS'),
        ],
        'auth' => [
            'key' => $env->offsetGet('AUTH_KEY'),
        ],
    ]);

    return $cfg;
})();

\ORM::configure(sprintf('mysql:host=%s;dbname=%s', $appCfg->get('db/host'), $appCfg->get('db/name')));
\ORM::configure('username', $appCfg->get('db/user'));
\ORM::configure('password', $appCfg->get('db/pass'));

$servicesRegistry = new Registry();
$servicesRegistry->set('auth', function () use ($appCfg) {

    return new AuthService($appCfg->get('auth/key'));
});
$router->getContainer()->singleton(ServicesInterface::class, new ServicesWrapper($servicesRegistry));


$helpers = [];
array_set($helpers, 'getPostContents', function (ServerRequest $request) {

    return $request->getBody()->getContents();
});
array_set($helpers, 'getServices', function () use ($router) {

    return $router->getContainer()->get(ServicesInterface::class);
});

return [
    $router,
    new class($helpers) extends AbstractModel {},
];
