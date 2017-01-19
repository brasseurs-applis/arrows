<?php

namespace BrasseursApplis\Arrows\App\Controller\Security;

use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\Repository\UserRepository;
use BrasseursApplis\Arrows\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserController
{
    /** @var PasswordEncoderInterface */
    private $passwordEncoder;

    /** @var UserRepository */
    private $userRepository;

    public function createUser()
    {
        $userName = 'toto';
        $rawPassword = 'toto';

        $salt = base64_encode(random_bytes(10));
        $password = $this->passwordEncoder->encodePassword($rawPassword, $salt);

        $user = new User(
            new UserId(Uuid::uuid4()),
            $userName,
            $password,
            $salt,
            []
        );

        $this->userRepository->persist($user);
    }
}
