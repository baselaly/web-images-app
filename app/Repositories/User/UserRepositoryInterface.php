<?php

namespace App\Repositories\User;

use App\User;

interface UserRepositoryInterface
{
    public function create(array $userData);
    public function getUserBy(array $columns = []);
    public function update(int $id, array $userData);
    public function getAllBy(array $columns = [], array $keywords = [], array $ids = []);
}
