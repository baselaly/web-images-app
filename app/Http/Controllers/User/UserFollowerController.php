<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowRequest;
use App\Http\Requests\unFollowRequest;
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
        $user = $userService->getAuthenticatedUser();
        $follow = $this->userFollowerService->followUser($user->id, request('user_id'));

        return $follow ? response()->json(['message' => 'followed successfully'], 200) :
        response()->json(['message' => 'You Already Follow This User'], 403);
    }

    public function unFollow(unFollowRequest $request)
    {
        $this->userFollowerService->unFollowUser(request('user_follow_id'));
        return response()->json(['message' => 'unfollowed successfully'], 200);
    }

}
