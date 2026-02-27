<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReopenHistory extends Model
{
    protected $table = 'reopen_history';

    protected $fillable = [
        'inventory_id',
        'reopened_by_user_id',
        'reopened_at',
        'justification',
    ];

    protected $casts = [
        'reopened_at' => 'datetime',
    ];

    public function inventory()
    {
        return $this->belongsTo(InventoryRecord::class, 'inventory_id');
    }

    public function reopenedBy()
    {
        return $this->belongsTo(User::class, 'reopened_by_user_id');
    }
}
