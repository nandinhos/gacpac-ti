<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class MilitaryUser extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory, SoftDeletes;

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
        'role',
        'registration',
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'custodian_user_id');
    }

    /**
     * Retorna os ativos vinculados a cautelas abertas (sem data de checkin)
     */
    public function activeCustodyAssets()
    {
        return $this->belongsToMany(Asset::class, 'custody_assets', 'custody_log_id', 'asset_id', null, 'id')
            ->whereHas('custodyLog', function($query) {
                $query->whereNull('checkin_date')
                      ->where('user_id', $this->id);
            });
    }

    // Nota: O relacionamento acima via belongsToMany direto pode ser complexo devido à estrutura.
    // Uma alternativa mais limpa para o contexto do app:
    public function currentCustodyAssets()
    {
        return Asset::whereHas('custodyLogs', function($query) {
            $query->whereNull('checkin_date')
                  ->where('user_id', $this->id);
        });
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
