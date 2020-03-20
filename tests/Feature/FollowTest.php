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
}
