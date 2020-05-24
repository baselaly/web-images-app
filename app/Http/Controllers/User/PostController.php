<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\Response\ErrorResource;
use App\Http\Resources\Response\SuccessResource;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function store(StorePostRequest $request, UserService $userService)
    {
        try {
            DB::beginTransaction();
            $user = $userService->getAuthenticatedUser();
            $this->postService->createPost($user->id);
            DB::commit();
            return response()->json(new SuccessResource('Post Created'), 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(new ErrorResource($e->getMessage()), 500);
        }
    }

}
