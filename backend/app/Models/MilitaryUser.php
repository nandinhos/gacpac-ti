<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilitaryUser extends Model
{
    protected $table = 'military_users';

    protected $fillable = [
        'name',
        'rank',
        'military_id',
        'sector_id',
        'email',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function custodyLogs()
    {
        return $this->hasMany(CustodyLog::class, 'user_id');
    }

    public function inventoryRecords()
    {
        return $this->hasMany(InventoryRecord::class, 'responsible_user_id');
    }
}
