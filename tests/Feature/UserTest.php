<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function getUserData()
    {
        return [
            'first_name' => "user",
            'last_name' => "user",
            'email' => "user@user.com",
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];
    }

    public function test_user_register()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', $userData);
        $response->assertJson(['message' => "user registered"]);
        $response->assertStatus(200);
    }

    public function test_user_register_with_image()
    {
        $userData = $this->getUserData();
        Storage::fake('users');
        $image = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['image' => $image]));
        $response->assertJson(['message' => "user registered"]);
        $response->assertStatus(200);
    }

    public function test_image_type_in_register()
    {
        $userData = $this->getUserData();
        Storage::fake('users');
        $image = UploadedFile::fake()->create('avatar.pdf');
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['image' => $image]));
        $response->assertStatus(422);
    }

    public function test_image_mimes_in_register()
    {
        $userData = $this->getUserData();
        Storage::fake('users');
        $image = UploadedFile::fake()->image('avatar.gif');
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['image' => $image]));
        $response->assertStatus(422);
    }

    public function test_image_size_in_register()
    {
        $userData = $this->getUserData();
        Storage::fake('users');
        $image = UploadedFile::fake()->image('avatar.jpg')->size(6000);
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['image' => $image]));
        $response->assertStatus(422);
    }

    public function test_required_first_name()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['first_name' => null]));
        $response->assertStatus(422);
    }

    public function test_max_length_first_name()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['first_name' => str_repeat('a', 251)]));
        $response->assertStatus(422);
    }

    public function test_required_last_name()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['last_name' => null]));
        $response->assertStatus(422);
    }

    public function test_max_length_last_name()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['last_name' => str_repeat('a', 251)]));
        $response->assertStatus(422);
    }

    public function test_email_format_for_register()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['email' => 'fakeEmail']));
        $response->assertStatus(422);
    }

    public function test_email_required_for_register()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['email' => null]));
        $response->assertStatus(422);
    }

    public function test_email_max_length_for_register()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['email' => str_repeat('a', 181) . '@gmail.com']));
        $response->assertStatus(422);
    }

    public function test_password_min_length_for_register()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['password' => '123456']));
        $response->assertStatus(422);
    }

    public function test_password_required_for_register()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['password' => null]));
        $response->assertStatus(422);
    }

    public function test_password_confirmation_for_register()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', array_merge($userData, [
            'password' => '12345678',
            'password_confirmation' => '1234567',
        ]));
        $response->assertStatus(422);
    }

    public function test_user_login_with_wrong_credentials()
    {
        $credentials = ['email' => 'user@user.com', 'password' => '12345678'];

        $response = $this->json('POST', '/api/user/login', $credentials);
        $response->assertStatus(401);
    }
}
