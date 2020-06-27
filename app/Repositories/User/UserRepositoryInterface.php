<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function create(array $userData);
    public function getUserBy(array $filters = []);
    public function update(int $id, array $userData);
    public function getAllBy(array $filters = [], int $paginate = 0);
}
