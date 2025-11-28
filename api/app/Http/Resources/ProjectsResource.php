<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectsResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->name,
            'description' => $this->description,
            'is_archived' => $this->is_archived,
            'fecha_creacion' => $this->created_at->format('d/m/Y H:i'),
            'fecha_actualizacion' => $this->updated_at->format('d/m/Y H:i'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
