<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sector extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function militaryUsers()
    {
        return $this->hasMany(MilitaryUser::class);
    }

    public function users()
    {
        return $this->hasMany(MilitaryUser::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function inventoryRecords()
    {
        return $this->hasMany(InventoryRecord::class);
    }
}
