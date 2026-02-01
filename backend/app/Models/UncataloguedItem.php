<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UncataloguedItem extends Model
{
    use HasFactory;
    protected $table = 'uncatalogued_items';

    protected $fillable = [
        'inventory_id',
        'description',
        'location',
        'found_date',
    ];

    protected $casts = [
        'found_date' => 'date',
    ];

    public function inventory()
    {
        return $this->belongsTo(InventoryRecord::class, 'inventory_id');
    }
}
