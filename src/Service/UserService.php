<?php

namespace BrasseursApplis\Arrows\Service;

use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\Repository\UserRepository;
use BrasseursApplis\Arrows\User;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserService
{
    /** @var UserRepository */
    private $userRepository;

    /** @var PasswordEncoderInterface */
    private $passwordEncoder;

    /**
     * UserService constructor.
     *
     * @param UserRepository           $userRepository
     * @param PasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param UserId   $userId
     * @param string   $password
     * @param string[] $roles
     */
    public function updateUser(UserId $userId, $password, array $roles)
    {
        $user = $this->userRepository->get($userId);

        if ($user === null) {
            throw new \InvalidArgumentException('User not found');
        }

        $this->update($user, $password, $roles);
    }

    /**
     * @param UserId   $userId
     * @param string   $userName
     * @param string   $password
     * @param string[] $roles
     */
    public function createUser(UserId $userId, $userName, $password, array $roles)
    {
        $user = new User($userId, $userName);

        $this->update($user, $password, $roles);
    }

    /**
     * @param User     $user
     * @param string   $password
     * @param string[] $roles
     */
    private function update(User $user, $password, array $roles)
    {
        $user->changePassword($password, $this->passwordEncoder);
        $user->updateRoles($roles);

        $this->userRepository->persist($user);
    }
}
