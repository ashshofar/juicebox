<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_list_of_posts()
    {
        // Create some posts
        Post::factory()->count(15)->create();

        // Call the index route
        $response = $this->getJson('/api/posts');

        // Assert success and the correct response structure
        $response->assertStatus(200);

        // Adjust the assertion to match the actual response structure
        $response->assertJsonStructure([
            'current_page',       // Current page number
            'data' => [           // List of posts
                '*' => [          // Assert structure of each post in the 'data' array
                    'id',
                    'user_id',
                    'title',
                    'content',
                    'created_at',
                    'updated_at',
                ]
            ],
            'first_page_url',     // Pagination links
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
    }

    public function test_can_show_post_by_id()
    {
        $post = Post::factory()->create();

        // Simulate the API request
        $response = $this->getJson("/api/posts/{$post->id}");

        // Assert success and the response structure
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'title', 'content', 'user_id']);
    }

    public function test_show_post_returns_404_when_not_found()
    {
        $response = $this->getJson('/api/posts/999'); // Non-existent post ID

        $response->assertStatus(404);
    }

    public function test_can_create_a_post()
    {
        $user = User::factory()->create();

        // Simulate user authentication
        Sanctum::actingAs($user);

        // Data to create a new post
        $postData = [
            'title' => 'New Post Title',
            'content' => 'New post content',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('posts', ['title' => 'New Post Title']);
    }

    public function test_create_post_fails_with_validation_errors()
    {
        $user = User::factory()->create();

        // Simulate user authentication
        Sanctum::actingAs($user);

        // Missing 'content' field
        $postData = [
            'title' => 'Incomplete Post',
        ];

        $response = $this->postJson('/api/posts', $postData);

        // Assert validation failure
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_can_update_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Simulate user authentication
        Sanctum::actingAs($user);

        $updateData = [
            'title' => 'Updated Post Title',
            'content' => 'Updated post content',
        ];

        $response = $this->patchJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', ['title' => 'Updated Post Title']);
    }

    public function test_cannot_update_post_that_belongs_to_another_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        // Simulate user authentication
        Sanctum::actingAs($user);

        $updateData = [
            'title' => 'Unauthorized Update',
        ];

        $response = $this->patchJson("/api/posts/{$post->id}", $updateData);

        // Assert unauthorized access
        $response->assertStatus(403);
    }

    public function test_can_delete_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Simulate user authentication
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Post deleted']);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_cannot_delete_post_that_belongs_to_another_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        // Simulate user authentication
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        // Assert unauthorized access
        $response->assertStatus(403);
    }
}
