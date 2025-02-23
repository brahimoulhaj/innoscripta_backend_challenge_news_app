<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'summary',
        'article_url',
        'image_url',
        'published_at',
        'source_id',
        'category_id',
        'author_id'
    ];
}
