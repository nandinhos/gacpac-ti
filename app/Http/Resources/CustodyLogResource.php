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
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->whenLoaded('asset')),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'sector_id' => $this->sector_id,
            'sector' => new SectorResource($this->whenLoaded('sector')),
            'date' => $this->date?->toISOString(),
            'return_date' => $this->return_date?->toISOString(),
            'checked_in_at' => $this->checked_in_at?->toISOString(),
            'notes' => $this->notes,
            'status' => $this->status,
            'number' => $this->number,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
