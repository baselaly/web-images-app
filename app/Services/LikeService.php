<?php

namespace App\Services;

use App\Repositories\Like\LikeRepositoryInterface;

class LikeService
{
    private $like;

    public function __construct(LikeRepositoryInterface $like)
    {
        $this->like = $like;
    }

    public function likeToggle($likeableType, $likeableId, $userId)
    {
        if ($like = $this->checkLike($likeableType, $likeableId, $userId)) {
            $this->delete($like->id);
            return $like->likeable->likes->count();
        }

        $likeData = [
            'user_id' => $userId,
            'likeable_type' => $likeableType,
            'likeable_id' => $likeableId
        ];

        $like = $this->like->create($likeData);
        return $like->likeable->likes->count();
    }

    public function checkLike($likeableType, $likeableId, $userId)
    {
        $getData = [
            'user_id' => $userId,
            'likeable_type' => $likeableType,
            'likeable_id' => $likeableId,
        ];

        return $this->like->getSingleBy($getData);
    }

    public function delete($id)
    {
        $this->like->delete($id);
    }
}
