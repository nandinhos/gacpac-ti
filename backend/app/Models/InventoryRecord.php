<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRecord extends Model
{
    protected $table = 'inventory_records';

    protected $fillable = [
        'commission_number',
        'start_date',
        'end_date',
        'sector_id',
        'responsible_user_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function responsibleUser()
    {
        return $this->belongsTo(MilitaryUser::class, 'responsible_user_id');
    }

    public function inventoryAssets()
    {
        return $this->hasMany(InventoryAsset::class, 'inventory_id');
    }

    public function uncataloguedItems()
    {
        return $this->hasMany(UncataloguedItem::class, 'inventory_id');
    }

    // Compatibilidade com frontend
    public function getFoundItemsAttribute()
    {
        return $this->inventoryAssets()->with('asset')->get()->map(function ($inventoryAsset) {
            $asset = $inventoryAsset->asset;
            if ($asset) {
                $asset->inventoryObservation = $inventoryAsset->observation;
                return $asset;
            }
            return null;
        })->filter();
    }

    public function reopenHistory()
    {
        return $this->hasMany(ReopenHistory::class);
    }
}
