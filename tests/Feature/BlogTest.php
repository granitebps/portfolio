<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class BlogTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    /** @test */
    public function test_get_blogs()
    {
        $response = $this->json('GET', '/api/v1/blog');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_get_blog()
    {
        $response = $this->json('GET', '/api/v1/blog/99');
        $response->assertStatus(404);

        $this->authenticate();
        $blog = $this->createBlog();

        $response = $this->json('GET', '/api/v1/blog/' . $blog->id . '/' . $blog->slug);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_create_blog()
    {
        Storage::fake('public');

        $response = $this->json('POST', '/api/v1/blog', $this->blogData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', '/api/v1/blog', $this->blogData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Blog Created'
        ]);
    }

    /** @test */
    public function test_update_blog()
    {
        $response = $this->json('PUT', '/api/v1/blog/99', $this->blogData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/blog/99', $this->blogData());
        $response->assertStatus(404);

        $blog = $this->createBlog();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/blog/' . $blog->id, $this->blogData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Blog Updated'
        ]);
    }

    /** @test */
    public function test_delete_blog()
    {
        $response = $this->json('DELETE', '/api/v1/blog/99');
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/blog/99');
        $response->assertStatus(404);

        $blog = $this->createBlog();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/blog/' . $blog->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Blog Deleted'
        ]);
    }

    public function blogData()
    {
        $file = UploadedFile::fake()->image('blog.jpg')->size(512);

        return [
            'title' => 'Test Blog Title',
            'body' => 'Test Blog Body',
            'image' => $file,
        ];
    }

    public function createBlog()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('blog.jpg');

        $user = User::first();

        $blog = Blog::create([
            'user_id' => $user->id,
            'title' => 'Test Blog Title',
            'slug' => 'test-blog-title',
            'body' => 'Test Blog Body',
            'image' => $file,
        ]);
        return $blog;
    }
}
