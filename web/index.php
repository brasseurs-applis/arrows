<?php

use BrasseursApplis\Arrows\App\ApplicationBuilder;
use BrasseursApplis\Arrows\App\ApplicationConfig;

require dirname(__DIR__) . '/vendor/autoload.php';

(new ApplicationBuilder(new ApplicationConfig(true)))->httpApplication()->run();
