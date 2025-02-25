<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'article_url' => $this->article_url,
            'image_url' => $this->image_url,
            'published_at' => $this->published_at,
            'published_at_hum' => Carbon::parse($this->published_at)->diffForHumans(),
            'source' => $this->whenLoaded('source'),
            'category' => $this->whenLoaded('category'),
            'author' => $this->whenLoaded('author'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
