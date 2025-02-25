<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use Filterable, HasFactory, Searchable;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'article_url',
        'image_url',
        'published_at',
        'source_id',
        'category',
        'author',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function getFilterableColumns(): array
    {
        return [
            'category.name' => 'relationship',
            'source.name' => 'relationship',
            'author' => 'string',
            'published_at' => 'date',
        ];
    }

    protected function getSearchableColumns(): array
    {
        return ['title', 'summary'];
    }
}
