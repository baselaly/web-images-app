<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Post\SinglePostResource;
use App\Http\Resources\Response\ErrorResource;
use App\Http\Resources\Response\NotAuthorizedResource;
use App\Http\Resources\Response\NotFoundResource;
use App\Http\Resources\Response\SuccessResource;
use App\Services\PostService;
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

    public function getHomePosts()
    {
        $posts = $this->postService->getHomePosts(auth('api')->user());
        return response()->json([
            'posts' => PostResource::collection($posts),
            'more_data' => $posts->hasMorePages(),
        ], 200);
    }

    public function getPost($id)
    {
        $post = $this->postService->getSinglePost($id);

        if (!$post) {
            return response()->json(new NotFoundResource(request()), 404);
        }

        if (!$this->postService->checkPostView($post)) {
            return response()->json(new NotAuthorizedResource('Not Authorized'), 403);
        }

        return response()->json(new SinglePostResource($post), 200);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $post = $this->postService->getSinglePost($id);

            if (!$post) {
                return response()->json(new NotFoundResource(request()), 404);
            }

            if ($post->user_id != auth('api')->user()->id) {
                return response()->json(new NotAuthorizedResource('Not Authorized'), 403);
            }

            $this->postService->delete($id);
            DB::commit();

            return response()->json(new SuccessResource('Post Deleted'), 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(new ErrorResource($e->getMessage()), 500);
        }
    }

    public function update($id, UpdatePostRequest $request)
    {
        try {
            DB::beginTransaction();
            $post = $this->postService->getSinglePost($id);

            if (!$post) {
                return response()->json(new NotFoundResource(request()), 404);
            }

            if ($post->user_id != auth('api')->user()->id) {
                return response()->json(new NotAuthorizedResource('Not Authorized'), 403);
            }

            $this->postService->update($id);
            DB::commit();

            return response()->json(new SuccessResource('Post Updated'), 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(new ErrorResource($e->getMessage()), 500);
        }
    }
}
