<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'property_id',
        'room_number',
        'room_name',
        'description',
        'price',
        'price_type',
        'area',
        'beds',
        'amenities',
        'images',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'amenities' => 'array',
        'images' => 'array',
        'is_available' => 'boolean',
        'area' => 'integer',
        'beds' => 'integer',
    ];

    /**
     * العلاقة مع الوحدة
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * العلاقة مع الحجوزات
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }

    /**
     * الحصول على الحجوزات النشطة
     */
    public function activeBookings()
    {
        return $this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_out_date', '>=', now()->toDateString());
    }

    /**
     * التحقق من توفر الغرفة في تاريخ معين
     */
    public function isAvailableOnDate($date)
    {
        if (!$this->is_available) {
            return false;
        }

        return !$this->activeBookings()
            ->where(function($query) use ($date) {
                $query->where('check_in_date', '<=', $date)
                      ->where(function($q) use ($date) {
                          $q->whereNull('check_out_date')
                            ->orWhere('check_out_date', '>=', $date);
                      });
            })
            ->exists();
    }

    /**
     * الحصول على أول صورة
     */
    public function getFirstImageAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            return $this->images[0];
        }
        return null;
    }
}

