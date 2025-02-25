<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{

    use HasFactory;

    protected $fillable = [
        "name",
        "url",
        "api_key"
    ];

    protected $hidden = ['created_at', 'updated_at', 'api_key'];

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
