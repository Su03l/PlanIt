<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'username' => $this->username,
            'email' => $this->email,
            'job_title' => $this->job_title,
            'bio' => $this->bio,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'social_links' => $this->social_links,
            'timezone' => $this->timezone,
            'joined_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
