<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustodyLog extends Model
{
    use HasFactory;
    protected $table = 'custody_logs';

    /**
     * Alias for user_id to maintain compatibility with unit tests.
     */
    public function getResponsibleIdAttribute()
    {
        return $this->user_id;
    }

    public function setResponsibleIdAttribute($value)
    {
        $this->user_id = $value;
    }

    protected $fillable = [
        'cautela_number',
        'user_id',
        'checkout_date',
        'checkin_date',
        'term_url',
        'signed_term_url',
        'notes',
    ];

    protected $casts = [
        'checkout_date' => 'datetime',
        'checkin_date' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(MilitaryUser::class, 'user_id');
    }

    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'custody_assets')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->whereNull('checkin_date');
    }

    public function scopeClosed($query)
    {
        return $query->whereNotNull('checkin_date');
    }

    // Accessors
    public function getStatusAttribute()
    {
        return $this->checkin_date ? 'Baixada' : 'Aberta';
    }
}
