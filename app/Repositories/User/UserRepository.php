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

    public function create($userData)
    {
        $this->user->create($userData);
    }
}
