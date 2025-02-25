<?php

namespace App\Contracts;

use App\Models\Source;

interface NewsSourceInterface
{
    public function fetch(Source $source): array;
    public function transform(array $data): array;
}
