<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestCannotCreateArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_article(): void
    {
        $payload = [
            'title' => 'Unauthorized Article',
            'content' => 'This should not be stored',
        ];

        $response = $this->postJson('/api/articles', $payload);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('articles', [
            'title' => 'Unauthorized Article',
        ]);
    }
}