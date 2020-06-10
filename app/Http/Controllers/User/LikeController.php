<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikePostRequest;
use App\Http\Resources\Like\LikeResource;
use App\Services\LikeService;

class LikeController extends Controller
{
    private $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function likePost(LikePostRequest $request)
    {
        $likesCount = $this->likeService->likeToggle('App\Post', $request->post_id, auth('api')->user()->id);
        return response()->json(new LikeResource($likesCount), 200);
    }
}
