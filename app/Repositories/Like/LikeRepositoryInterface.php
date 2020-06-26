<?php

namespace App\Repositories\Like;
interface LikeRepositoryInterface
{
    public function create(array $data);
    public function getSingleBy(array $data = []);
    public function delete(int $id);
}
