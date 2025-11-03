<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory;
    protected $fillable = [
        'qr_code',
        'name',
        'brand',
        'model', 
        'serial_number',
        'patrimony_number',
        'type',
        'category',
        'status',
        'condition',
        'sector_id',
        'acquisition_date',
        'warranty_expiry',
        'purchase_value',
        'notes',
        // Legacy fields - manter para compatibilidade
        'qr_code',
        'name',
        'subcategory',
        'description',
        'patrimony_id',
        'manufacturer',
        'purchase_price',
        'condition_rating',
        'location',
        'custodian_user_id',
        'conta',
        'categoria_inventario',
        'bmp',
        'componente',
        'situacao',
        'qtd',
        'valor_atualizado',
        'deprec_acumulada',
        'valor_liquido',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'warranty_expiry' => 'date',
        'purchase_price' => 'decimal:2',
        'condition_rating' => 'integer',
        'qtd' => 'integer',
        'valor_atualizado' => 'decimal:2',
        'deprec_acumulada' => 'decimal:2',
        'valor_liquido' => 'decimal:2',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function custodian()
    {
        return $this->belongsTo(MilitaryUser::class, 'custodian_user_id');
    }

    public function photos()
    {
        return $this->hasMany(AssetPhoto::class);
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function custodyLogs()
    {
        return $this->belongsToMany(CustodyLog::class, 'custody_assets');
    }
}
