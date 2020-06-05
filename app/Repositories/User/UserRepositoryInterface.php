<?php

namespace App\Repositories\User;

use App\User;

interface UserRepositoryInterface
{
    public function create(array $userData);
    public function getUserBy(array $columns = []);
    public function update(User $user, array $userData = []);
}
