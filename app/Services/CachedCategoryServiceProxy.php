<?php

namespace App\Services;

use App\Services\Interfaces\CategoryServiceInterface;
use App\DTO\CreateCategoryDTO;
use App\DTO\UpdateCategoryDTO;
use App\DTO\CategoryResponseDTO;
use Illuminate\Support\Facades\Cache;

class CachedCategoryServiceProxy implements CategoryServiceInterface
{
    private const CACHE_KEY = 'categories_tree_v1';
    private CategoryServiceInterface $service;
    private int $ttl;

    public function __construct(CategoryServiceInterface $service, int $ttl = 3600)
    {
        $this->service = $service;
        $this->ttl = $ttl;
    }

    public function getTree(): array
    {
        return Cache::remember(self::CACHE_KEY, $this->ttl, fn() => $this->service->getTree());
    }

    public function getById(int $id): CategoryResponseDTO
    {
        // don't cache single items here (optional)
        return $this->service->getById($id);
    }

    public function create(CreateCategoryDTO $dto): CategoryResponseDTO
    {
        $result = $this->service->create($dto);
        $this->invalidateCache();
        return $result;
    }

    public function update(UpdateCategoryDTO $dto): CategoryResponseDTO
    {
        $result = $this->service->update($dto);
        $this->invalidateCache();
        return $result;
    }

    public function delete(int $id): void
    {
        $this->service->delete($id);
        $this->invalidateCache();
    }

    protected function invalidateCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
