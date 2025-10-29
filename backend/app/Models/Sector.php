<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function militaryUsers()
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
