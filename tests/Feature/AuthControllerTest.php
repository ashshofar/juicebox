<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Jobs\SendWelcomeEmail;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully()
    {
        Queue::fake(); // Mock the queue to prevent actual job dispatch

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'User registered successfully']);

        // Assert user was added to the database
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);

        // Assert that the job was pushed to the queue
        Queue::assertPushed(SendWelcomeEmail::class);
    }

    public function test_register_fails_when_required_fields_are_missing()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'johndoe@example.com', // Name and password missing
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'password']);
    }

    public function test_register_fails_password_confirmation_mismatch()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_register_fails_if_email_is_not_unique()
    {
        // Create a user with the same email
        User::factory()->create(['email' => 'johndoe@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_login_successfully()
    {
        // Create a user
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_login_fails_when_fields_are_missing()
    {
        $response = $this->postJson('/api/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);

        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@example.com',
            'password' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_logout_successfully()
    {
        $user = User::factory()->create();

        // Simulate a logged-in user with Sanctum
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logged out successfully']);
    }

    public function test_logout_fails_when_user_is_not_authenticated()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    public function test_show_user_with_valid_id()
    {
        // Create a user
        $user = User::factory()->create();

        // Simulate the user being authenticated
        Sanctum::actingAs($user);

        // Call the API and pass the user's ID
        $response = $this->getJson('/api/users/' . $user->id);

        // Assert that the response is successful (HTTP 200)
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'email']);
    }

    public function test_show_user_with_invalid_id()
    {
        // Create a valid user to authenticate with Sanctum
        $user = User::factory()->create();

        // Simulate authentication for the valid user
        Sanctum::actingAs($user);

        // Make a request with an invalid user ID (999)
        $response = $this->getJson('/api/users/999');

        // Assert that the response status is 404 (Not Found)
        $response->assertStatus(404);
    }
}

