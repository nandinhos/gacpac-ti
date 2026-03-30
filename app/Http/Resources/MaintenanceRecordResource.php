<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->whenLoaded('asset')),
            'type' => $this->type,
            'description' => $this->description,
            'cost' => $this->cost,
            'maintenance_date' => $this->maintenance_date?->toDateString(),
            'completion_date' => $this->completion_date?->toDateString(),
            'provider' => $this->provider,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
