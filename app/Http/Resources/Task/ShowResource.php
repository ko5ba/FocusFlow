<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
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
            'tags' => $this->tags->isNotEmpty() ? $this->tags->pluck('title') : null,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at && $this->updated_at->ne($this->created_at)
                ? $this->updated_at->format('Y-m-d H:i')
                : null,
            'deleted_at' => $this->deleted_at ?? null
        ];
    }
}
