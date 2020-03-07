<?php

namespace App\Repositories\User;

use App\User;

class UserRepository implements UserRepositoryInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $userData)
    {
        return $this->user->create($userData);
    }
}
