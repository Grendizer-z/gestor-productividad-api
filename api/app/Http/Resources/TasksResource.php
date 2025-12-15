<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
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
            'projects_id' => $this->projects_id,
            'title' => $this->title,
            'due_date' => $this->due_date,
            'is_completed' => $this->is_completed,
            'fecha_creacion' => $this->created_at->format('d/m/Y H:i'),
            'fecha_actualizacion' => $this->updated_at->format('d/m/Y H:i'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
