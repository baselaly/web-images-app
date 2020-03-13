<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use JWTAuth;

class UserService
{

    private $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    public function register()
    {
        $userData = request()->except('password_confirmation');
        $this->user->create($userData);
    }

    public function login()
    {
        $credentials = [
            'email' => request('email'),
            'password' => request('password'),
            'active' => 1,
        ];

        return JWTAuth::attempt($credentials);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function getAuthenticatedUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function getActiveUser($userId)
    {
        return $this->user->getUserBy(['id' => $userId, 'active' => 1]);
    }
}
