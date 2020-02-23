<?php

namespace Tests\Unit;

use Tests\TestCase;

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
        $this->withoutMiddleware();
        $response = $this->json('POST', 'http://localhost:8000/api/user/register', $userData);
        // $response->assertJson(['message' => "user registered"]);
        $response->assertStatus(200);
    }
}
