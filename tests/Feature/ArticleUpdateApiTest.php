<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleUpdateApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function author_can_update_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $payload = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ];

        $response = $this->actingAs($user)->putJson("/api/articles/{$article->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonPath('data.title', 'Updated Title');

        $this->assertDatabaseHas('articles', ['title' => 'Updated Title']);
    }

    /** @test */
    public function guest_cannot_update_article()
    {
        $article = Article::factory()->create();

        $payload = ['title' => 'Updated Title'];

        $response = $this->putJson("/api/articles/{$article->id}", $payload);

        $response->assertStatus(401); // Unauthorized
    }

    /** @test */
    public function non_owner_cannot_update_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(); 

        $payload = ['title' => 'Updated Title'];

        $response = $this->actingAs($user)->putJson("/api/articles/{$article->id}", $payload);

        $response->assertStatus(403); // Forbidden
    }
}