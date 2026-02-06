<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

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
        'is_primary',
        'file_size',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'is_primary' => 'boolean',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function getStorageUrlAttribute(): string
    {
        return Storage::url($this->url);
    }
}
