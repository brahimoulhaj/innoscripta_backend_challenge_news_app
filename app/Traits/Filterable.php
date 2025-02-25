<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

trait Filterable
{
    public function scopeFilter(Builder $query, ?string $filters): Builder
    {
        if (! $filters) {
            return $query;
        }

        try {
            $filters = array_map(function ($filter) {
                $filter = explode(':', $filter);
                $key = $filter[0];
                $value = explode(',', $filter[1]);

                return [$key => count($value) > 1 ? $value : $value[0]];
            }, explode(';', $filters));

            $filters = array_merge(...$filters);

            $filterableColumns = $this->getFilterableColumns();
            $columns = array_intersect(array_keys($filterableColumns), array_keys($filters));

            foreach ($columns as $column) {
                if ($filterableColumns[$column] === 'date') {
                    if (is_array($filters[$column])) {
                        $query->whereBetween($column, $filters[$column]);
                    } else {
                        $query->whereDate($column, '>=', (string)$filters[$column]);
                    }
                } elseif ($filterableColumns[$column] === 'relationship') {
                    $value = $filters[$column];
                    $column = explode('.', $column);
                    if (is_array($value)) {
                        $query->whereHas($column[0], fn($q) => $q->whereIn($column[1], $value));
                    } else {
                        $query->whereRelation($column[0], $column[1], $value);
                    }
                } else {
                    $query->where($column, $filters[$column]);
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return $query;
    }

    protected function getFilterableColumns(): array
    {
        throw new Exception('Not implemented');
    }
}
