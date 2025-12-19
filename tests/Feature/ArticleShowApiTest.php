<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleShowApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_show_article_with_comments()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);

        $comments = Comment::factory()->count(2)->create([
            'article_id' => $article->id,
            'user_id' => $user->id
        ]);

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200);

        $response->assertJsonPath('data.id', $article->id);

        $response->assertJsonCount(2, 'data.comments');
    }

    public function test_returns_404_if_article_not_found()
    {
        $response = $this->getJson('/api/articles/999');

        $response->assertStatus(404);
    }
}