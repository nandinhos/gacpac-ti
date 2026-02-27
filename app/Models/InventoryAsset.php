<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAsset extends Model
{
    protected $table = 'inventory_assets';

    protected $fillable = [
        'inventory_id',
        'asset_id',
        'observation',
    ];

    public function inventory()
    {
        return $this->belongsTo(InventoryRecord::class, 'inventory_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
