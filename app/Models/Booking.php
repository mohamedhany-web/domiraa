<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'room_id',
        'property_id',
        'user_id',
        'inspection_date',
        'inspection_time',
        'booking_date',
        'viewing_date',
        'booking_type',
        'amount',
        'payment_status',
        'status',
        'contract_signed',
        'confirmed_at',
        'cancelled_at',
        'cancellation_reason',
        'contract_path',
        'contract_uploaded_at',
        'booking_code',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'inspection_time' => 'datetime',
        'amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'contract_uploaded_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class);
    }
    
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
    
    /**
     * Get booking code attribute
     */
    public function getBookingCodeAttribute(): string
    {
        if (isset($this->attributes['booking_code']) && $this->attributes['booking_code']) {
            return $this->attributes['booking_code'];
        }
        
        // Generate booking code if not exists
        return 'BOOK-' . str_pad($this->id, 8, '0', STR_PAD_LEFT);
    }
}
