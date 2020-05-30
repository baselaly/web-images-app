<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\Post\SinglePostResource;
use App\Http\Resources\Response\ErrorResource;
use App\Http\Resources\Response\NotFoundResource;
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

    public function store(StorePostRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->postService->createPost(auth('api')->user()->id);
            DB::commit();
            return response()->json(new SuccessResource('Post Created'), 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(new ErrorResource($e->getMessage()), 500);
        }
    }

    public function getPublicPost($id)
    {
        $post = $this->postService->getSingleActivePost($id);

        if (!$post) {
            return response()->json(new NotFoundResource(request()), 404);
        }

        return response()->json(new SinglePostResource($post), 200);
    }
}
