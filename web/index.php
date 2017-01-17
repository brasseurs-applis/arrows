<?php

use BrasseursApplis\Arrows\App\Controller\IndexController;
use BrasseursApplis\Arrows\App\Controller\ObserverController;
use BrasseursApplis\Arrows\App\Controller\PositionOneController;
use BrasseursApplis\Arrows\App\Controller\PositionTwoController;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new Application(['debug'=>true]);
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider(), [ 'twig.path' => dirname(__DIR__) . '/views' ]);

$app['index.controller'] = function() use ($app) {
    return new IndexController($app['twig']);
};

$app['observer.controller'] = function() use ($app) {
    return new ObserverController($app['twig']);
};

$app['positionOne.controller'] = function() use ($app) {
    return new PositionOneController($app['twig']);
};

$app['positionTwo.controller'] = function() use ($app) {
    return new PositionTwoController($app['twig']);
};

$app->get('/', 'index.controller:indexAction');
$app->get('/session/{sessionId}/observer', 'observer.controller:showAction');
$app->get('/session/{sessionId}/one', 'positionOne.controller:showAction');
$app->get('/session/{sessionId}/two', 'positionTwo.controller:showAction');

$app->run();
