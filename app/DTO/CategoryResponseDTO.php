<?php

namespace App\DTO;

class CategoryResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public ?int $parent_id,
        public array $children = []
    ) {}
}
