<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use App\Helpers\StorageHelper;

class Property extends Model
{
    protected $fillable = [
        'user_id',
        'ownership_proof',
        'property_type_id',
        'address',
        'location_lat',
        'location_lng',
        'status',
        'price',
        'price_type',
        'contract_duration',
        'contract_duration_type',
        'annual_increase',
        'video_url',
        'special_requirements',
        'available_dates',
        'admin_status',
        'admin_notes',
        'rejection_reason',
        'is_suspended',
        'suspended_until',
        'quality_score',
        'quality_details',
        'area',
        'rooms',
        'bathrooms',
        'floor',
        'amenities',
        'views_count',
        'is_room_rentable',
        'total_rooms',
    ];

    protected $casts = [
        'available_dates' => 'array',
        'price' => 'decimal:2',
        'annual_increase' => 'decimal:2',
        'is_suspended' => 'boolean',
        'suspended_until' => 'datetime',
        'quality_details' => 'array',
        'amenities' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }
    
    public function complaints(): HasMany
    {
        return $this->hasMany(\App\Models\Complaint::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class)->orderBy('room_number');
    }

    public function availableRooms()
    {
        return $this->rooms()->where('is_available', true);
    }

    public function getCodeAttribute(): string
    {
        return 'PROP-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get the video URL attribute
     */
    public function getVideoUrlAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }
        
        // If it's already a full URL (YouTube, Vimeo, etc.)
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        // If it's a local file path, convert to URL
        $path = ltrim($value, '/');
        
        if (Storage::disk('public')->exists($path)) {
            return StorageHelper::url($path);
        }
        
        return null;
    }
    
    /**
     * Get the first image URL or placeholder
     */
    public function getFirstImageUrlAttribute(): string
    {
        $firstImage = $this->images()->first();
        
        if ($firstImage) {
            return $firstImage->url;
        }
        
        return StorageHelper::placeholder();
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }
}
