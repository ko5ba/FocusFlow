<?php

namespace App\Http\Resources\Category;

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_a && $this->updated_at->ne($this->created_at)
                ? $this->updated_at->format('Y-m-d H:i')
                : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i') : null
        ];
    }
}
