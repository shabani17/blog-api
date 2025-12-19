<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleListApiTest extends TestCase
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