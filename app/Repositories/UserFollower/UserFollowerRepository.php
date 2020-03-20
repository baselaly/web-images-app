<?php

namespace App\Repositories\UserFollower;

use App\UserFollower;

class UserFollowerRepository implements UserFollowerRepositoryInterface
{
    private $userFollower;

    public function __construct(UserFollower $userFollower)
    {
        $this->userFollower = $userFollower;
    }

    public function create(array $followData)
    {
        return $this->userFollower->create($followData);
    }

    public function getBy(array $columns = [])
    {
        $follows = $this->userFollower->newQuery();

        foreach ($columns as $column => $value) {
            $follows->where($column, $value);
        }

        $follows = $follows->get();

        return $follows;
    }

    public function delete($id)
    {
        $this->userFollower->find($id)->delete();
    }
}
