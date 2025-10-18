<?php

namespace App\Services;

use App\Services\Interfaces\CategoryServiceInterface;
use App\Models\Category;
use App\DTO\CreateCategoryDTO;
use App\DTO\UpdateCategoryDTO;
use App\DTO\CategoryResponseDTO;

class CategoryService implements CategoryServiceInterface
{
    public function getTree(): array
    {
        $categories = Category::all();

        $tree = $this->buildTree($categories);

        return $tree;
    }

    protected function buildTree($categories, $parentId = null): array
    {
        return $categories->where('parent_id', $parentId)->map(function ($category) use ($categories) {
            return new CategoryResponseDTO(
                $category->id,
                $category->name,
                $category->description,
                $category->parent_id,
                array_values($this->buildTree($categories, $category->id)) // array_values превращает в массив
            );
        })->toArray();
    }

    public function getById(int $id): CategoryResponseDTO
    {
        $c = Category::findOrFail($id);
        return new CategoryResponseDTO($c->id, $c->name, $c->description, $c->parent_id, []);
    }

    public function create(CreateCategoryDTO $dto): CategoryResponseDTO
    {
        $c = Category::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'parent_id' => $dto->parent_id,
        ]);

        return new CategoryResponseDTO($c->id, $c->name, $c->description, $c->parent_id, []);
    }

    public function update(UpdateCategoryDTO $dto): CategoryResponseDTO
    {
        $c = Category::findOrFail($dto->id);
        $c->update([
            'name' => $dto->name,
            'description' => $dto->description,
            'parent_id' => $dto->parent_id,
        ]);

        return new CategoryResponseDTO($c->id, $c->name, $c->description, $c->parent_id, []);
    }

    public function delete(int $id): void
    {
        $c = Category::findOrFail($id);
        $c->delete();
    }
}
