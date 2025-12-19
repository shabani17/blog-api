<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentUpdateApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_comment()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
            'user_id' => $user->id,
            'body' => 'Original comment',
        ]);

        $payload = ['body' => 'Updated comment'];

        $response = $this
            ->actingAs($user)
            ->putJson("/api/comments/{$comment->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonPath('data.body', 'Updated comment');

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Updated comment',
        ]);
    }

    public function test_guest_cannot_update_comment()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
            'user_id' => $user->id,
        ]);

        $payload = ['body' => 'Attempted update'];

        $response = $this->putJson("/api/comments/{$comment->id}", $payload);

        $response->assertStatus(401); // Unauthorized
    }

    public function test_other_user_cannot_update_comment()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $owner->id]);
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
            'user_id' => $owner->id,
            'body' => 'Original comment',
        ]);

        $payload = ['body' => 'Malicious update'];

        $response = $this
            ->actingAs($otherUser)
            ->putJson("/api/comments/{$comment->id}", $payload);

        $response->assertStatus(403); // Forbidden
    }
}