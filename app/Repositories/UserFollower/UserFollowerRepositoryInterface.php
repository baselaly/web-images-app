<?php

namespace App\Repositories\UserFollower;

interface UserFollowerRepositoryInterface
{
    public function create(array $followData);
    public function getBy(array $columns = [], array $with = []);
    public function delete(int $userFollowId);
}
