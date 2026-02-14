<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class MaintenanceRecord extends Model
{
    use HasFactory, Auditable;
    protected $table = 'maintenance_records';

    protected $fillable = [
        'asset_id',
        'type',
        'date',
        'description',
        'performed_by',
        'cost',
        'next_maintenance_date',
        'notes',
        'is_upgrade',
        'parts_replaced',
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
        'next_maintenance_date' => 'date',
        'is_upgrade' => 'boolean',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function scopeUpcoming($query, $days = 30)
    {
        return $query->whereNotNull('next_maintenance_date')
            ->whereBetween('next_maintenance_date', [now(), now()->addDays($days)]);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<', now());
    }
}
