<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MilitaryUser extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $table = 'military_users';

    protected $fillable = [
        'name',
        'rank',
        'military_id',
        'sector_id',
        'email',
        'phone',
        'password',
        'is_active',
        'user_role',
        'commission_inventories',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'commission_inventories' => 'array',
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
