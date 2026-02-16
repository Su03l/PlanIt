<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'assigned_to' => new UserResource($this->whenLoaded('assignee')),
            'created_by' => $this->creator->full_name,
            'due_date' => $this->due_date?->format('Y-m-d H:i'),
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
