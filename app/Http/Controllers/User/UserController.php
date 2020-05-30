<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Response\ErrorResource;
use App\Http\Resources\Response\NotAuthenticatedResource;
use App\Http\Resources\Response\NotFoundResource;
use App\Http\Resources\Response\SuccessResource;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->userService->register();
            DB::commit();
            return response()->json(new SuccessResource('user resgistered successfully'), 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(new ErrorResource($e->getMessage()), 500);
        }
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $token = $this->userService->login();
            if (!$token) {
                return response()->json(new NotAuthenticatedResource($request), 401);
            }
            return response()->json(compact('token'), 200);
        } catch (\Exception $e) {
            return response()->json(new ErrorResource($e->getMessage()), 500);
        }
    }

    public function logout()
    {
        try {
            $this->userService->logout();
            return response()->json(['message' => 'user logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(new ErrorResource($e->getMessage()), 500);
        }
    }

    public function getMyProfile(PostService $postService)
    {
        $user = auth('api')->user();
        $posts = $postService->getUserPosts($user->id);
        return response()->json([
            'user' => $user,
            'posts' => PostResource::collection($posts),
            'more_data' => $posts->hasMorePages(),
        ], 200);
    }

    public function getUserProfile($userId, $slug, PostService $postService)
    {
        $user = $this->userService->getActiveUser($userId);
        if (!$user) {
            return response()->json(new NotFoundResource(request()), 404);
        }
        $posts = $postService->getUserActivePosts($user->id);
        return response()->json([
            'user' => $user,
            'posts' => PostResource::collection($posts),
            'more_data' => $posts->hasMorePages(),
        ], 200);
    }
}
