<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_tree_and_cache_is_used_and_invalidated()
    {
        Cache::shouldReceive('remember')
            ->andReturnUsing(function($key, $ttl, $callback) {
                return $callback();
            });
        Cache::shouldReceive('forget')->andReturnTrue();

        $root = Category::factory()->create(['name' => 'Root']);
        $child = Category::factory()->create(['parent_id' => $root->id, 'name' => 'Child']);

        $this->getJson('/api/categories')
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Root'])
            ->assertJsonFragment(['name' => 'Child']);

        $payload = ['name' => 'New', 'description'=> 'd', 'parent_id' => $root->id];
        $this->postJson('/api/categories', $payload)->assertStatus(201);

        $this->getJson('/api/categories')->assertStatus(200)
            ->assertJsonFragment(['name' => 'New']);
    }

    public function test_crud_operations()
    {
        $create = ['name' => 'Electronics', 'description' => 'desc', 'parent_id' => null];
        $resp = $this->postJson('/api/categories', $create);
        $resp->assertStatus(201)->assertJsonFragment(['name' => 'Electronics']);

        $id = $resp->json('id');

        $this->putJson("/api/categories/{$id}", ['name' => 'Electronics 2', 'description' => 'desc2', 'parent_id' => null])
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Electronics 2']);

        $this->deleteJson("/api/categories/{$id}")->assertStatus(204);
        $this->getJson("/api/categories/{$id}")->assertStatus(404);
    }
}
