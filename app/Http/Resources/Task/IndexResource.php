<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
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
            'priority' => $this->priority,
            'date_deadline' => $this->date_deadline ?? null,
            'status' => $this->status,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'title' => $this->category->title
            ] : null,
            'tags' => $this->tags ? $this->tags->pluck('title') : null
        ];
    }
}
