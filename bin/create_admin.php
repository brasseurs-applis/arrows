<?php

use BrasseursApplis\Arrows\App\ApplicationBuilder;
use BrasseursApplis\Arrows\App\ApplicationConfig;
use BrasseursApplis\Arrows\App\Finder\UserFinder;
use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\Service\UserService;
use BrasseursApplis\Arrows\User;
use Ramsey\Uuid\Uuid;

require dirname(__DIR__) . '/vendor/autoload.php';

$container = (new ApplicationBuilder(new ApplicationConfig(true)))->container();

/** @var UserService $userService */
$userService = $container['arrows.user.service'];

/** @var UserFinder $userFinder */
$userFinder = $container['arrows.user.finder'];

$userService->createUser(new UserId((string) Uuid::uuid4()), 'admin', 'admin', [ User::ROLE_ADMIN ]);
