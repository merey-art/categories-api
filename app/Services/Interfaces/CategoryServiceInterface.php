<?php

namespace App\Services\Interfaces;

use App\DTO\CreateCategoryDTO;
use App\DTO\UpdateCategoryDTO;
use App\DTO\CategoryResponseDTO;
use Illuminate\Support\Collection;

interface CategoryServiceInterface
{
    public function getTree(): array;
    public function getById(int $id): CategoryResponseDTO;
    public function create(CreateCategoryDTO $dto): CategoryResponseDTO;
    public function update(UpdateCategoryDTO $dto): CategoryResponseDTO;
    public function delete(int $id): void;
}
