<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function getPostData()
    {
        return [
            'body' => "body",
        ];
    }

    public function getImages($imagesNumbers)
    {
        $imagesData = array();

        for ($i = 0; $i < $imagesNumbers; $i++) {
            Storage::fake('images');
            $image = UploadedFile::fake()->image('post.jpg');
            $imagesData[] = $image;
        }

        return $imagesData;
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

    public function test_store_post()
    {
        $user = factory('App\User')->create();
        $jwtToken = $this->headers($user);
        $postData = $this->getPostData();
        $imagesData = $this->getImages(5);

        $response = $this->json(
            'POST',
            '/api/post/store',
            array_merge($postData, ['images' => $imagesData]),
            $jwtToken
        );
        $response->assertJson(['message' => "Post Created"]);
        $response->assertStatus(200);
    }

    public function test_store_post_for_not_authenticated_user()
    {
        $jwtToken = $this->headers();
        $postData = $this->getPostData();
        $imagesData = $this->getImages(5);

        $response = $this->json(
            'POST',
            '/api/post/store',
            array_merge($postData, ['images' => $imagesData]),
            $jwtToken
        );
        $response->assertStatus(500);
    }

    public function test_store_post_without_images()
    {
        $user = factory('App\User')->create();
        $jwtToken = $this->headers($user);
        $postData = $this->getPostData();

        $response = $this->json(
            'POST',
            '/api/post/store',
            array_merge($postData),
            $jwtToken
        );
        $response->assertStatus(422);
    }

    public function test_store_post_with_six_images()
    {
        $user = factory('App\User')->create();
        $jwtToken = $this->headers($user);
        $postData = $this->getPostData();
        $imagesData = $this->getImages(6);

        $response = $this->json(
            'POST',
            '/api/post/store',
            array_merge($postData, ['images' => $imagesData]),
            $jwtToken
        );

        $response->assertStatus(422);
    }

    public function test_store_post_with_max_body()
    {
        $user = factory('App\User')->create();
        $jwtToken = $this->headers($user);
        $postData = $this->getPostData();
        $imagesData = $this->getImages(5);

        $response = $this->json(
            'POST',
            '/api/post/store',
            array_merge($postData, ['images' => $imagesData, 'body' => str_repeat('a', 5001)]),
            $jwtToken
        );

        $response->assertStatus(422);
    }

    public function test_store_post_with_wrong_images_mimes()
    {
        $user = factory('App\User')->create();
        $jwtToken = $this->headers($user);
        $postData = $this->getPostData();
        $imagesData = array();

        Storage::fake('images');
        $image = UploadedFile::fake()->image('post.gif');
        $imagesData[] = $image;

        $response = $this->json(
            'POST',
            '/api/post/store',
            array_merge($postData, ['images' => $imagesData]),
            $jwtToken
        );

        $response->assertStatus(422);
    }

    public function test_store_post_with_wrong_images_sizes()
    {
        $user = factory('App\User')->create();
        $jwtToken = $this->headers($user);
        $postData = $this->getPostData();
        $imagesData = array();

        Storage::fake('images');
        $image = UploadedFile::fake()->image('post.jpg')->size(5001);
        $imagesData[] = $image;

        $response = $this->json(
            'POST',
            '/api/post/store',
            array_merge($postData, ['images' => $imagesData]),
            $jwtToken
        );

        $response->assertStatus(422);
    }

    public function test_store_post_with_wrong_images_types()
    {
        $user = factory('App\User')->create();
        $jwtToken = $this->headers($user);
        $postData = $this->getPostData();
        $imagesData = array();

        Storage::fake('images');
        $image = UploadedFile::fake()->create('post.pdf');
        $imagesData[] = $image;

        $response = $this->json(
            'POST',
            '/api/post/store',
            array_merge($postData, ['images' => $imagesData]),
            $jwtToken
        );

        $response->assertStatus(422);
    }

    public function test_delete_post()
    {
        $user = factory('App\User')->create();
        $jwtToken = $this->headers($user);
        $post = factory('App\Post')->create(['user_id' => $user->id]);

        $response = $this->json('GET', "/api/post/delete/$post->id", [], $jwtToken);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Post Deleted']);
    }

    public function test_delete_post_of_another_user()
    {
        $user = factory('App\User')->create();
        $fakeUser = factory('App\User')->create();
        $jwtToken = $this->headers($fakeUser);
        $post = factory('App\Post')->create(['user_id' => $user->id]);

        $response = $this->json('GET', "/api/post/delete/$post->id", [], $jwtToken);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'Not Authorized']);
    }

    public function test_delete_not_found_post()
    {
        $user = factory('App\User')->create();
        $jwtToken = $this->headers($user);

        $response = $this->json('GET', "/api/post/delete/1000", [], $jwtToken);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Not Found']);
    }
}
