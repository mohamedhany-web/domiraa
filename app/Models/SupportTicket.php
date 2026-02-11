<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'guest_id',
        'name',
        'email',
        'phone',
        'subject',
        'status',
        'priority',
        'last_reply_at',
        'assigned_to',
    ];

    protected $casts = [
        'last_reply_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'TKT-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(SupportMessage::class, 'ticket_id')->latestOfMany();
    }

    public function unreadMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')->where('is_read', false)->where('is_admin', false);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'open' => 'مفتوح',
            'pending' => 'قيد الانتظار',
            'answered' => 'تم الرد',
            'closed' => 'مغلق',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'blue',
            'pending' => 'yellow',
            'answered' => 'green',
            'closed' => 'gray',
            default => 'gray',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low' => 'منخفضة',
            'medium' => 'متوسطة',
            'high' => 'عالية',
            default => $this->priority,
        };
    }
}

