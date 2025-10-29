<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetPhoto extends Model
{
    protected $table = 'asset_photos';

    protected $fillable = [
        'asset_id',
        'url',
        'caption',
        'uploaded_at',
        'mime_type',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
