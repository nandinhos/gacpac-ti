<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Auditable, HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_military',
        'force',
        'rank',
        'military_id',
        'organization',
        'sector_id',
        'is_active',
        'profile_photo_path',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? \Illuminate\Support\Facades\Storage::url($this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_military' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    // Relacionamentos migrados do MilitaryUser
    public function assets()
    {
        return $this->hasMany(Asset::class, 'custodian_user_id');
    }

    public function inventoryRecords()
    {
        return $this->hasMany(InventoryRecord::class, 'responsible_user_id');
    }

    public function custodyLogs()
    {
        return $this->hasMany(CustodyLog::class, 'user_id');
    }

    public function currentCustodyAssets()
    {
        return Asset::whereHas('custodyLogs', function ($query) {
            $query->where('user_id', $this->id)
                ->whereNull('checkin_date');
        });
    }
}
