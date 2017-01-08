<?php

use BrasseursDApplis\Arrows\App\Controller\IndexController;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider(), [ 'twig.path' => dirname(__DIR__) . '/views' ]);

$app['index.controller'] = function() use ($app) {
    return new IndexController($app['twig']);
};

$app->get('/', 'index.controller:indexAction');

$app->run();