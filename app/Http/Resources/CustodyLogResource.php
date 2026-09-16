<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustodyLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cautela_number' => $this->cautela_number,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'assets' => AssetResource::collection($this->whenLoaded('assets')),
            'checkout_date' => $this->checkout_date?->toISOString(),
            'checkin_date' => $this->checkin_date?->toISOString(),
            'term_url' => $this->term_url,
            'signed_term_url' => $this->signed_term_url,
            'notes' => $this->notes,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
