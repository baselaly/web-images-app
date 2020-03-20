<?php

namespace App\Services;

use App\Repositories\UserFollower\UserFollowerRepositoryInterface;

class UserFollowerService
{
    private $userFollower;

    public function __construct(UserFollowerRepositoryInterface $userFollower)
    {
        $this->userFollower = $userFollower;
    }

    public function followUser($followerId, $userId)
    {
        if ($this->checkFollowing($followerId, $userId)) {
            return false;
        }

        $followData = [
            'follower_id' => $followerId,
            'user_id' => $userId,
        ];

        $this->userFollower->create($followData);
        return true;
    }

    public function unFollowUser($userFollowId)
    {
        $this->userFollower->delete($userFollowId);
    }

    public function checkFollowing($followerId, $userId)
    {
        $followData = [
            'follower_id' => $followerId,
            'user_id' => $userId,
        ];

        $follows = $this->userFollower->getSingleBy($followData);

        return $follows ? true : false;
    }

    public function getUserFollowers($userId)
    {
        return $this->userFollower->getBy(['user_id' => $userId], ['follower']);
    }

    public function getUserFollowings($userId)
    {
        return $this->userFollower->getBy(['follower_id' => $userId], ['user']);
    }

}
