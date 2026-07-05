<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentUpdateApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $payload = ['body' => 'Updated comment content'];

        $response = $this->actingAs($user)->putJson("/api/comments/{$comment->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonPath('data.body', 'Updated comment content');

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'body' => 'Updated comment content']);
    }

    public function test_guest_cannot_update_comment()
    {
        $comment = Comment::factory()->create();

        $payload = ['body' => 'Updated comment content'];

        $response = $this->putJson("/api/comments/{$comment->id}", $payload);

        $response->assertStatus(401); // unauthorized
    }

    public function test_other_user_cannot_update_comment()
    {
        $commentOwner = User::factory()->create();
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $commentOwner->id]);

        $payload = ['body' => 'Malicious update attempt'];

        $response = $this->actingAs($otherUser)->putJson("/api/comments/{$comment->id}", $payload);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id, 'body' => 'Malicious update attempt']);
    }
}