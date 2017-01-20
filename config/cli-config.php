<?php

use BrasseursApplis\Arrows\App\ApplicationBuilder;
use BrasseursApplis\Arrows\App\ApplicationConfig;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__ . '/../vendor/autoload.php';

$container = (new ApplicationBuilder(new ApplicationConfig(true)))->container();

return ConsoleRunner::createHelperSet($container['orm.em']);