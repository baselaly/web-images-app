<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowRequest;
use App\Http\Requests\unFollowRequest;
use App\Http\Resources\Follower\FollowerResource;
use App\Http\Resources\Response\NotAuthorizedResource;
use App\Http\Resources\Response\SuccessResource;
use App\Services\UserFollowerService;
use App\Services\UserService;

class UserFollowerController extends Controller
{
    private $userFollowerService;

    public function __construct(UserFollowerService $userFollowerService)
    {
        $this->userFollowerService = $userFollowerService;
    }

    public function store(FollowRequest $request, UserService $userService)
    {
        $user = auth('api')->user();
        $follow = $this->userFollowerService->followUser($user->id, request('user_id'));

        return $follow ? response()->json(new SuccessResource('Followed Successfully'), 200) :
            response()->json(new NotAuthorizedResource('You Already Followed This User'), 403);
    }

    public function unFollow(unFollowRequest $request)
    {
        $this->userFollowerService->unFollowUser(request('user_follow_id'));
        return response()->json(new SuccessResource('UnFollowed Successfully'), 200);
    }

    public function getUserFollowers($userId)
    {
        $followers = $this->userFollowerService->getUserFollowers($userId);
        return response()->json([
            'followers' => FollowerResource::collection($followers),
            'more_data' => $followers->hasMorePages(),
        ], 200);
    }

    public function getUserFollowings($userId)
    {
        $followings = $this->userFollowerService->getUserFollowings($userId);
        return response()->json([
            'followings' => FollowerResource::collection($followings),
            'more_data' => $followings->hasMorePages(),
        ], 200);
    }
}
