<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_articles_list()
    {
        Article::factory()->count(3)->create();

        $response = $this->getJson('/api/articles');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}