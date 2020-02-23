<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    public function testUserRegister()
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
}
