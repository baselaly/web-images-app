<?php

namespace App\Repositories\UserFollower;

interface UserFollowerRepositoryInterface
{
    public function create(array $followData);
    public function getBy(array $columns = []);
    public function delete(int $userFollowId);
}
