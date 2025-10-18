<?php

namespace App\DTO;

class CreateCategoryDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public ?int $parent_id
    ) {}
}
