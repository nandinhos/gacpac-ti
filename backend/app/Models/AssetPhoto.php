<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetPhoto extends Model
{
    use HasFactory;
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
