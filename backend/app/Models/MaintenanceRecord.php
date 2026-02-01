<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceRecord extends Model
{
    use HasFactory;
    protected $table = 'maintenance_records';

    protected $fillable = [
        'asset_id',
        'date',
        'description',
        'performed_by',
        'cost',
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
