<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

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

    public function getUserNewData()
    {
        return [
            'first_name' => "user",
            'last_name' => "user",
            "bio" => "bio",
        ];
    }

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

    public function test_user_register()
    {
        $userData = $this->getUserData();
        $response = $this->json('POST', '/api/user/register', $userData);
        $response->assertJson(['message' => "user registered successfully"]);
        $response->assertStatus(200);
    }

    public function test_user_register_with_image()
    {
        $userData = $this->getUserData();
        Storage::fake('users');
        $image = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->json('POST', '/api/user/register', array_merge($userData, ['image' => $image]));
        $response->assertJson(['message' => "user registered successfully"]);
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

    public function test_get_my_profile_for_authenticated_user()
    {
        $user = factory('App\User')->create(['active' => 1]);
        $jwtToken = $this->headers($user);
        $response = $this->json('GET', '/api/user/profile', [], $jwtToken);
        $response->assertStatus(200);
    }

    public function test_get_my_profile_for_not_authenticated_user()
    {
        $jwtToken = $this->headers();
        $response = $this->json('GET', '/api/user/profile', [], $jwtToken);
        $response->assertStatus(401);
    }

    public function test_update_my_profile()
    {
        $user = factory('App\User')->create(['active' => 1]);
        $jwtToken = $this->headers($user);
        $newData = $this->getUserNewData();
        $response = $this->json('POST', '/api/user/edit/profile', $newData, $jwtToken);
        $response->assertJson(['message' => 'Profile Updated Successfully']);
        $response->assertStatus(200);
    }

    public function test_update_my_profile_for_not_authenticated_user()
    {
        $jwtToken = $this->headers();
        $newData = $this->getUserNewData();
        $response = $this->json('POST', '/api/user/edit/profile', $newData, $jwtToken);
        $response->assertStatus(401);
    }

    public function test_update_my_profile_with_missing_first_name()
    {
        $user = factory('App\User')->create(['active' => 1]);
        $jwtToken = $this->headers($user);
        $newData = $this->getUserNewData();
        $response = $this->json('POST', '/api/user/edit/profile', array_merge($newData, ['first_name' => '']), $jwtToken);
        $response->assertStatus(422);
    }

    public function test_update_my_profile_with_missing_last_name()
    {
        $user = factory('App\User')->create(['active' => 1]);
        $jwtToken = $this->headers($user);
        $newData = $this->getUserNewData();
        $response = $this->json('POST', '/api/user/edit/profile', array_merge($newData, ['last_name' => '']), $jwtToken);
        $response->assertStatus(422);
    }

    public function test_update_my_profile_with_max_bio()
    {
        $user = factory('App\User')->create(['active' => 1]);
        $jwtToken = $this->headers($user);
        $newData = $this->getUserNewData();
        $response = $this->json('POST', '/api/user/edit/profile', array_merge($newData, ['bio' => str_repeat('a', 1001)]), $jwtToken);
        $response->assertStatus(422);
    }

    public function test_update_my_profile_with_pdf_as_image()
    {
        $user = factory('App\User')->create(['active' => 1]);
        $jwtToken = $this->headers($user);
        $newData = $this->getUserNewData();
        $response = $this->json('POST', '/api/user/edit/profile', array_merge(
            $newData,
            ['image' => UploadedFile::fake()->create('post.pdf')]
        ), $jwtToken);
        $response->assertStatus(422);
    }

    public function test_update_my_profile_with_as_overloaded_image_size()
    {
        $user = factory('App\User')->create(['active' => 1]);
        $jwtToken = $this->headers($user);
        $newData = $this->getUserNewData();
        $response = $this->json('POST', '/api/user/edit/profile', array_merge(
            $newData,
            ['image' => UploadedFile::fake()->create('post.jpg')->size(5001)]
        ), $jwtToken);
        $response->assertStatus(422);
    }

    public function test_update_my_profile_with_password()
    {
        $user = factory('App\User')->create(['active' => 1]);
        $jwtToken = $this->headers($user);
        $newData = $this->getUserNewData();
        $response = $this->json('POST', '/api/user/edit/profile', array_merge(
            $newData,
            ['password' => "12345678", 'password_confirmation' => "12345678"]
        ), $jwtToken);
        $response->assertStatus(200);
    }

    public function test_update_my_profile_with_password_confirmation_missing()
    {
        $user = factory('App\User')->create(['active' => 1]);
        $jwtToken = $this->headers($user);
        $newData = $this->getUserNewData();
        $response = $this->json('POST', '/api/user/edit/profile', array_merge(
            $newData,
            ['password' => "12345678"]
        ), $jwtToken);
        $response->assertStatus(422);
    }

    public function test_update_my_profile_with_password_less_than_min()
    {
        $user = factory('App\User')->create(['active' => 1]);
        $jwtToken = $this->headers($user);
        $newData = $this->getUserNewData();
        $response = $this->json('POST', '/api/user/edit/profile', array_merge(
            $newData,
            ['password' => "123", "password_confirmation" => "123"]
        ), $jwtToken);
        $response->assertStatus(422);
    }
}
