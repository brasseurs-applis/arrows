<?php

namespace BrasseursApplis\Arrows\Repository;

use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\User;

interface UserRepository
{
    /**
     * @param UserId $id
     *
     * @return User
     */
    public function get(UserId $id);

    /**
     * @param User $user
     *
     * @return void
     */
    public function persist(User $user);

    /**
     * @param User $user
     *
     * @return void
     */
    public function delete(User $user);
}
