<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReopenHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'inventory_reopen_histories';

    protected $fillable = [
        'inventory_id',
        'reopened_by_user_id',
        'justification',
        'reopened_at',
    ];

    public function inventory()
    {
        return $this->belongsTo(InventoryRecord::class, 'inventory_id');
    }

    public function user()
    {
        return $this->belongsTo(MilitaryUser::class, 'reopened_by_user_id');
    }
}