<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class InventoryRecord extends Model
{
    use HasFactory, Auditable;
    protected $table = 'inventory_records';

    protected $fillable = [
        'commission_number',
        'start_date',
        'end_date',
        'sector_id',
        'responsible_user_id',
        'status',
        'notes',
        'is_commission',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_commission' => 'boolean',
    ];

    protected $appends = ['found_items', 'uncatalogued_items', 'pending_items', 'summary'];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function inventoryAssets()
    {
        return $this->hasMany(InventoryAsset::class, 'inventory_id');
    }

    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'inventory_assets', 'inventory_id', 'asset_id');
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
                $asset->observation = $inventoryAsset->observation;
                return $asset;
            }
            return null;
        })->filter()->values();
    }
    
    // Atributo para itens não catalogados - retornar objetos completos
    public function getUncataloguedItemsAttribute()
    {
        return $this->uncataloguedItems()->with('createdBy')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'description' => $item->description,
                'created_at' => $item->created_at,
                'created_by_user_id' => $item->created_by_user_id,
                'creator_name' => $item->createdBy->name ?? 'Unknown',
            ];
        })->values();
    }
    
    // Calcular itens pendentes (faltantes) = todos assets do setor - encontrados
    public function getPendingItemsAttribute()
    {
        $foundAssetIds = $this->inventoryAssets()->pluck('asset_id')->toArray();
        
        $query = \App\Models\Asset::query();
        
        // Se tem setor específico, filtrar por setor, senão pegar todos
        if ($this->sector_id) {
            $query->where('sector_id', $this->sector_id);
        }
        
        // Excluir os já encontrados
        if (!empty($foundAssetIds)) {
            $query->whereNotIn('id', $foundAssetIds);
        }
        
        return $query->get();
    }
    
    // Resumo do inventário
    public function getSummaryAttribute()
    {
        $foundCount = $this->inventoryAssets()->count();
        $uncataloguedCount = $this->uncataloguedItems()->count();
        
        // Calcular total de assets do setor
        $totalQuery = \App\Models\Asset::query();
        if ($this->sector_id) {
            $totalQuery->where('sector_id', $this->sector_id);
        }
        $totalCount = $totalQuery->count();
        
        $pendingCount = $totalCount - $foundCount;
        
        return [
            'total' => $totalCount,
            'found' => $foundCount,
            'pending' => $pendingCount,
            'uncatalogued' => $uncataloguedCount
        ];
    }

    public function reopenHistory()
    {
        return $this->hasMany(ReopenHistory::class, 'inventory_id');
    }

    public function commissionMembers()
    {
        return $this->belongsToMany(User::class, 'inventory_commission_members', 'inventory_record_id', 'military_user_id');
    }
}
