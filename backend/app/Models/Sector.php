<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Sector extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

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
