<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use JWTAuth;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        return $headers;
    }

    public function test_follow_user()
    {
        $follower = factory('App\User')->create();
        $jwtToken = $this->headers($follower);
        $user = factory('App\User')->create();
        $response = $this->json('POST', '/api/user/follow',
            array_merge(['user_id' => $user->id]), $jwtToken);
        $response->assertJson(['message' => "followed successfully"]);
        $response->assertStatus(200);
    }

    public function test_follow_user_to_himself()
    {
        $follower = factory('App\User')->create();
        $jwtToken = $this->headers($follower);
        $response = $this->json('POST', '/api/user/follow',
            array_merge(['user_id' => $follower->id]), $jwtToken);
        $response->assertStatus(422);
    }

    public function test_follow_user_without_user_id()
    {
        $follower = factory('App\User')->create();
        $jwtToken = $this->headers($follower);
        $response = $this->json('POST', '/api/user/follow',
            array_merge([]), $jwtToken);
        $response->assertStatus(422);
    }

    public function test_follow_user_to_already_followed_user()
    {
        $follower = factory('App\User')->create();
        $user = factory('App\User')->create();

        $jwtToken = $this->headers($follower);

        $this->json('POST', '/api/user/follow',
            array_merge(['user_id' => $user->id]), $jwtToken);

        $response = $this->json('POST', '/api/user/follow',
            array_merge(['user_id' => $user->id]), $jwtToken);

        $response->assertJson(['message' => "You Already Follow This User"]);
        $response->assertStatus(403);
    }

    public function test_unfollow_user()
    {
        $user = factory('App\User')->create();
        $follower = factory('App\User')->create();

        $jwtToken = $this->headers($follower);

        $userFollow = factory('App\UserFollower')->create([
            'user_id' => $user->id,
            'follower_id' => $follower->id,
        ]);

        $response = $this->json('POST', '/api/user/unfollow',
            ['user_follow_id' => $userFollow->id], $jwtToken);

        $response->assertJson(['message' => "unfollowed successfully"]);
        $response->assertStatus(200);
    }

    public function test_unfollow_user_with_wrong_id()
    {
        $user = factory('App\User')->create();
        $follower = factory('App\User')->create();

        $jwtToken = $this->headers($follower);

        $userFollow = factory('App\UserFollower')->create([
            'user_id' => $user->id,
            'follower_id' => $follower->id,
        ]);

        $response = $this->json('POST', '/api/user/unfollow',
            array_merge(['user_follow_id' => $follower->id]), $jwtToken);

        $response->assertStatus(422);
    }

    public function test_unfollow_user_with_wrong_follower()
    {
        $user = factory('App\User')->create();
        $follower = factory('App\User')->create();

        $jwtToken = $this->headers($user);

        $userFollow = factory('App\UserFollower')->create([
            'user_id' => $user->id,
            'follower_id' => $follower->id,
        ]);

        $response = $this->json('POST', '/api/user/unfollow',
            array_merge(['user_follow_id' => $userFollow->id]), $jwtToken);

        $response->assertStatus(422);
    }

    public function test_unfollow_user_without_id()
    {
        $user = factory('App\User')->create();
        $follower = factory('App\User')->create();

        $jwtToken = $this->headers($user);

        $userFollow = factory('App\UserFollower')->create([
            'user_id' => $user->id,
            'follower_id' => $follower->id,
        ]);

        $response = $this->json('POST', '/api/user/unfollow',
            array_merge([]), $jwtToken);

        $response->assertStatus(422);
    }
}
