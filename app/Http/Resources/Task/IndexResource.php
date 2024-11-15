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
            'priority' => $this->priority ?? null,
            'date_deadline' => $this->date_deadline ?? null,
            'status' => $this->status ?? null,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'title' => $this->category->title
            ] : null
        ];
    }
}
