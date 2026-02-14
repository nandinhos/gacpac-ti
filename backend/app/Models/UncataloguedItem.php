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
        'created_by_user_id',
    ];

    protected $casts = [
        'found_date' => 'date',
    ];

    public function inventory()
    {
        return $this->belongsTo(InventoryRecord::class, 'inventory_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
