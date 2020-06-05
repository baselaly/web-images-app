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

    public function getUserBy(array $columns = [])
    {
        $user = $this->user->newQuery();

        foreach ($columns as $column => $value) {
            $user->where($column, $value);
        }

        return $user->first();
    }

    public function update(User $user, array $userData)
    {
        return $user->update($userData);
    }
}
