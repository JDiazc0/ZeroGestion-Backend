<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * 
 * Transform user model into a Json representation.
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'email' => $this->email,
            'theme_id' => $this->theme_id,
            'create_at' => $this->created_at,
            'update_at' => $this->update_at,
        ];
    }
}
