<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'logo' => $this->logo ? asset('storage/' . $this->logo) : null,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'members_count' => $this->users_count ?? $this->users()->count(),
            'created_at' => $this->created_at->format('Y-m-d'),
            // Current user's role in this group
            'my_role' => $this->users()
                ->where('user_id', $request->user()->id)
                ->first()
                ?->pivot
                ?->role,
        ];
    }
}
