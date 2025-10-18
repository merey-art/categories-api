<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Root categories
        Category::factory()->count(5)->create()->each(function($root) {
            // level 1 children
            Category::factory()->count(rand(1,4))->create(['parent_id' => $root->id])->each(function($child) {
                // level 2 children
                Category::factory()->count(rand(0,3))->create(['parent_id' => $child->id]);
            });
        });
    }
}
