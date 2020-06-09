<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikePostRequest;
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
        $this->likeService->likeToggle('App\Post', $request->post_id, auth('api')->user()->id);
        return 'done';
    }
}
