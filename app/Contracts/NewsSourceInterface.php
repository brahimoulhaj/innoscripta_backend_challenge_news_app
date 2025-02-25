<?php

namespace App\Contracts;

use App\Models\Category;
use App\Models\Source;

interface NewsSourceInterface
{
    public function fetch(Source $source, Category $category): array;

    public function transform(array $data): array;
}
