<?php

namespace App\Repositories\Like;

use App\Like;

interface LikeRepositoryInterface
{
    public function create(array $data);
    public function getSingleBy(array $data = []);
    public function delete(int $id);
}
