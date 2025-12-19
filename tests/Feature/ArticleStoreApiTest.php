<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleStoreApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_article()
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Test Article',
            'content' => 'This is a test body',
        ];

        $response = $this
            ->actingAs($user)
            ->postJson('/api/articles', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.title', 'Test Article');

        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
        ]);
    }

    public function test_guest_cannot_create_article()
    {
        $payload = [
            'title' => 'Test Article',
            'content' => 'This is a test body',
        ];

        $response = $this->postJson('/api/articles', $payload);

        $response->assertStatus(401); // Unauthorized
    }
}