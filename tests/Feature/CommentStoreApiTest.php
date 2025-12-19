<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentStoreApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_guest_cannot_create_comment()
    {
        $article = Article::factory()->create();

        $payload = [
            'body' => 'Test comment',
        ];

        $response = $this->postJson("/api/articles/{$article->id}/comments", $payload);

        $response->assertStatus(401); // Unauthorized
    }

    /** @test */
    public function test_authenticated_user_can_create_comment()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $payload = [
            'body' => 'Test comment',
        ];

        $response = $this
            ->actingAs($user)
            ->postJson("/api/articles/{$article->id}/comments", $payload);

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.body', 'Test comment');

        $this->assertDatabaseHas('comments', [
            'body' => 'Test comment',
            'user_id' => $user->id,
            'article_id' => $article->id,
        ]);
    }

    /** @test */
    public function test_validation_errors_when_creating_comment()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $payload = [];

        $response = $this
            ->actingAs($user)
            ->postJson("/api/articles/{$article->id}/comments", $payload);

        $response->assertStatus(422); // Validation error
        $response->assertJsonValidationErrors('body');
    }
}