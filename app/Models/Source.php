<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    protected $fillable = [
        "name", "url", "api_key"
    ];

    protected function casts(): array 
    {
        return [
            "api_key" => "encrypted"
        ];
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
