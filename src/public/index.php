<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Config;
use Phalcon\Mvc\Router;

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require '../vendor/autoload.php';

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader->register();

$container = new FactoryDefault();
$application = new Application($container);


$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => 'root', "password" => "password123"));

        return $mongo;
    },
    true
);

$container->set(
    'router',
    function () {
        $router = new Router();

        // $router->setDefaultModule('front');

        $router->add(
            '/admin/index',
            [
                'module'     => 'admin',
                'controller' => 'index',
                'action'     => 'index',
            ]
        );

        $router->add(
            '/admin/products/:action',
            [
                'module'     => 'admin',
                'controller' => 'products',
                'action'     => 1,
            ]
        );
        $router->add(
            '/admin/:action',
            [
                'module'     => 'admin',
                'controller' => 'login',
                'action'     => 1,
            ]
        );

       
        $router->add(
            '/',
            [
                'module'     => 'front',
                'controller' => 'two',
                'action'     => 'products',
            ]
        );
        $router->add(
            'front/index',
            [
                'module'     => 'front',
                'controller' => 'index',
                'action'     => 'index',
            ]
        );

        return $router;
    }
);


$application->registerModules(
    [
        
        'admin'  => [
            'className' => \Multi\Admin\Module::class,
            'path'      => APP_PATH.'/admin/Module.php',
        ],
        'front'  => [
            'className' => \Multi\Front\Module::class,
            'path'      => APP_PATH.'/front/Module.php',
        ]
    ]
);


try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
