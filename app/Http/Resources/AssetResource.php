<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'model' => $this->model,
            'brand' => $this->brand,
            'serial_number' => $this->serial_number,
            'qr_code' => $this->qr_code,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'category' => $this->relationLoaded('category') ? new CategoryResource($this->getRelation('category')) : null,
            'sector_id' => $this->sector_id,
            'sector' => $this->relationLoaded('sector') ? new SectorResource($this->getRelation('sector')) : null,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
