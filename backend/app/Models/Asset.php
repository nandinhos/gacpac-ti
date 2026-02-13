<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // Identificação Principal
        'qr_code',
        'name',
        'patrimony_number',
        'serial_number',

        // Classificação e Detalhes
        'brand',
        'model',
        'type',
        'category',
        'category_id',
        'description', // Mantido pois é útil, mesmo se não obrigatório
        
        // Estado e Localização
        'status',
        'condition',
        'sector_id',
        'location',
        'custodian_user_id',
        'notes',

        // Financeiro
        'acquisition_date',
        'warranty_expiry',
        'purchase_value',

        // --- Campos Legados / Compatibilidade (Manter enquanto houver dependência) ---
        'patrimony_id',      // = patrimony_number
        'manufacturer',      // = brand
        'subcategory',       // (sem uso direto no novo, mas mantido)
        'purchase_price',    // = purchase_value
        'condition_rating',  // = condition (int vs string)
        'conta',
        'categoria_inventario',
        'bmp',
        'componente',
        'situacao',
        'qtd',
        'valor_atualizado',
        'deprec_acumulada',
        'valor_liquido',
        'is_modified',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'warranty_expiry' => 'date',
        'purchase_value' => 'decimal:2',
        
        // Casts de compatibilidade
        'purchase_price' => 'decimal:2',
        'condition_rating' => 'integer',
        'qtd' => 'integer',
        'valor_atualizado' => 'decimal:2',
        'deprec_acumulada' => 'decimal:2',
        'valor_liquido' => 'decimal:2',
        'is_modified' => 'boolean',
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
