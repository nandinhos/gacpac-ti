<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'qr_code',
        'name',
        'category',
        'subcategory',
        'description',
        'serial_number',
        'patrimony_id',
        'manufacturer',
        'model',
        'acquisition_date',
        'warranty_expiry',
        'purchase_price',
        'status',
        'condition_rating',
        'sector_id',
        'location',
        'custodian_user_id',
        'notes',
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
