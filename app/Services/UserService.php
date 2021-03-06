<?php

namespace App\Services;

use App\Jobs\SendMail;
use App\Mail\RegisterMail;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
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
        $token = uniqid() . time();
        $userData = [
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'password' => request('password'),
            'email' => request('email'),
            'activation_token' => $token,
        ];

        $this->user->create($userData);
        $email = new RegisterMail('Welcome Mail', 'Welcome To Your Web Images App', $token);
        $welcomeEmail = (new SendMail(request('email'), $email))->delay(Carbon::now()->addSeconds(5));
        dispatch($welcomeEmail);
    }

    public function login()
    {
        $credentials = [
            'email' => request('email'),
            'password' => request('password'),
            'active' => 1,
        ];

        return auth('api')->attempt($credentials);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function getActiveUser($userId)
    {
        return $this->user->getUserBy(['id' => $userId, 'active' => 1]);
    }

    public function update($userId)
    {
        $userData = [
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'image' => request('image'),
            'bio' => request('bio'),
        ];

        request('password') ? $userData['password'] = request('password') : '';

        $this->user->update($userId, $userData);
    }

    public function activateUser($token)
    {
        $user = $this->user->getUserBy(['activation_token' => $token]);

        if (!$user) {
            return false;
        }

        $data = ['active' => 1, 'activation_token' => null];
        $this->user->update($user->id, $data);
        return true;
    }

    public function getUserFollowers($userId)
    {
        $user = $this->getActiveUser($userId);
        $followersIds = $user->followers->pluck('follower_id')->toArray();
        return $this->user->getAllBy(['user_ids' => $followersIds]);
    }
}
