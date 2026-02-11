<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Helpers\StorageHelper;

class PropertyImage extends Model
{
    protected $fillable = [
        'property_id',
        'image_path',
        'thumbnail_path',
        'order',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the full URL of the image
     */
    public function getUrlAttribute(): string
    {
        return StorageHelper::url($this->image_path);
    }

    /**
     * Get the full URL of the thumbnail
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path) {
            return StorageHelper::url($this->thumbnail_path);
        }
        
        // Try to find thumbnail automatically
        return StorageHelper::thumbnailUrl($this->image_path);
    }
}
