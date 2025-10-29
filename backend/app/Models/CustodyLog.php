<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustodyLog extends Model
{
    protected $table = 'custody_logs';

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

    public function user()
    {
        return $this->belongsTo(MilitaryUser::class, 'user_id');
    }

    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'custody_assets');
    }
}
