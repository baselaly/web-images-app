<?php

namespace App\Repositories\Like;

use App\Like;

class LikeRepository implements LikeRepositoryInterface
{
    protected $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function create(array $data)
    {
        return $this->like->create($data);
    }

    public function getSingleBy(array $data = [])
    {
        $like = $this->like->newQuery();

        foreach ($data as $column => $value) {
            $like->where($column, $value);
        }

        return $like->first();
    }

    public function delete($id)
    {
        return $this->like->where('id', $id)->delete();
    }
}
