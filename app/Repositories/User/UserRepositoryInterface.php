<?php

namespace App\Repositories\User;
interface UserRepositoryInterface
{
    public function create(array $userData);
    public function getUserBy(array $columns = []);
    public function update(int $id, array $userData);
}
