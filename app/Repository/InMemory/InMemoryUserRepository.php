<?php

namespace BrasseursApplis\Arrows\App\Repository\InMemory;

use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\Repository\UserRepository;
use BrasseursApplis\Arrows\User;

class InMemoryUserRepository implements UserRepository
{
    /** @var User[] */
    private $users;

    /**
     * @param UserId $id
     *
     * @return User
     */
    public function get(UserId $id)
    {
        if (! isset($this->users[(string) $id])) {
            return null;
        }

        return $this->users[(string) $id];
    }

    /**
     * @param string $userName
     *
     * @return User
     */
    public function getByUserName($userName)
    {
        $users = array_filter($this->users, function (User $user) use ($userName) {
            return $user->getUserName() === $userName;
        });

        if (count($users) > 0) {
            return $users[0];
        }

        return null;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function persist(User $user)
    {
        $this->users[(string) $user->getId()] = $user;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function delete(User $user)
    {
        if (! isset($this->users[(string) $user->getId()])) {
            return;
        }

        unset($this->users[(string) $user->getId()]);
    }
}
