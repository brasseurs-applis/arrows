<?php

use BrasseursApplis\Arrows\App\ApplicationBuilder;
use BrasseursApplis\Arrows\App\ApplicationConfig;
use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\User;
use Ramsey\Uuid\Uuid;

require __DIR__ . '/vendor/autoload.php';

$container = (new ApplicationBuilder(new ApplicationConfig(true)))->container();

$passwordEncoder = $container['security.default_encoder'];
$userRepository = $container['arrows.user.repository'];

$userName = 'toto';
$rawPassword = 'toto';
$salt = base64_encode(random_bytes(10));
$password = $passwordEncoder->encodePassword($rawPassword, $salt);


$user = new User(
    new UserId(Uuid::uuid4()),
    $userName,
    $password,
    $salt,
    [ User::ROLE_ADMIN, User::ROLE_RESEARCHER ]
);

$userRepository->persist($user);
