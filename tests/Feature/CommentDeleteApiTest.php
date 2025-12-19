<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentDeleteApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_delete_own_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->actingAs($user)
            ->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }

    /** @test */
    public function user_cannot_delete_others_comment()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $otherUser->id
        ]);

        $response = $this
            ->actingAs($user)
            ->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(403); // Forbidden

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id
        ]);
    }

    /** @test */
    public function guest_cannot_delete_comment()
    {
        $comment = Comment::factory()->create();

        $response = $this->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(401); // Unauthorized
    }
}