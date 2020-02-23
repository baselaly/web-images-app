<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_user_register()
    {
        $userData = [
            'first_name' => "user",
            'last_name' => "user",
            'email' => "user@user.com",
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];
        $response = $this->json('POST', '/api/user/register', $userData);
        $response->assertJson(['message' => "user registered"]);
        $response->assertStatus(200);
    }

    public function test_required_first_name()
    {
        $userData = [
            'last_name' => "user",
            'email' => "user@user.com",
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];
        $response = $this->json('POST', '/api/user/register', $userData);
        $response->assertSessionHasErrors('first_name');
        $response->assertStatus(402);
    }
}
