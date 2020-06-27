<?php

namespace App\Repositories\User;

use App\QueryFilters\User\ActivationTokenFilter;
use App\QueryFilters\User\ActiveFilter;
use App\QueryFilters\User\KeywordFilter;
use App\QueryFilters\User\UserIdsFilter;
use App\User;
use Illuminate\Pipeline\Pipeline;

class UserRepository implements UserRepositoryInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $userData)
    {
        return $this->user->create($userData);
    }

    public function getUserBy(array $filters = [])
    {
        return app(Pipeline::class)
            ->send($this->user->query())
            ->through([
                new UserIdsFilter($filters),
                new ActiveFilter($filters),
                new ActivationTokenFilter($filters),
                new KeywordFilter($filters)
            ])
            ->thenReturn()->first();
    }

    public function update(int $id, array $userData)
    {
        return $this->user->where('id', $id)->update($userData);
    }

    public function getAllBy(array $filters = [], int $paginate = 0)
    {
        $users = app(Pipeline::class)
            ->send($this->user->query())
            ->through([
                new UserIdsFilter($filters),
                new ActiveFilter($filters),
                new ActivationTokenFilter($filters),
                new KeywordFilter($filters)
            ])
            ->thenReturn();

        $users = $paginate && $paginate !== 0 ? $users->paginate($paginate) : $users->get();
        return $users;
    }
}
