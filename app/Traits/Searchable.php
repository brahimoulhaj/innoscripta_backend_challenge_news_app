<?php

namespace App\Traits;

use Exception;

trait Searchable
{
    public function scopeSearch($query, $search)
    {
        if ($search == null || $search == '') {
            return $query;
        }

        $columns = $this->getSearchableColumns();

        return $query->where(function ($query) use ($search, $columns) {
            for ($i = 0; $i < count($columns); $i++) {
                if ($i === 0) {
                    $query->where($columns[$i], 'like', "%$search%");
                } else {
                    $query->orWhere($columns[$i], 'like', "%$search%");
                }
            }
        });
    }

    protected function getSearchableColumns(): array
    {
        throw new Exception('Not implemented');
    }
}
