<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'description',
        'notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    // العلاقات
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // العنصر المرتبط (دفعة، مصروف، الخ)
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->whereIn('type', ['income', 'transfer_in']);
    }

    public function scopeExpense($query)
    {
        return $query->whereIn('type', ['expense', 'transfer_out']);
    }

    public function scopeByWallet($query, $walletId)
    {
        return $query->where('wallet_id', $walletId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // الوظائف المساعدة
    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'income' => 'إيراد',
            'expense' => 'مصروف',
            'transfer_in' => 'تحويل وارد',
            'transfer_out' => 'تحويل صادر',
            'refund' => 'استرداد',
            'adjustment' => 'تعديل',
            default => $this->type,
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'income', 'transfer_in' => 'green',
            'expense', 'transfer_out' => 'red',
            'refund' => 'yellow',
            'adjustment' => 'blue',
            default => 'gray',
        };
    }

    public function isIncome(): bool
    {
        return in_array($this->type, ['income', 'transfer_in']);
    }

    public function isExpense(): bool
    {
        return in_array($this->type, ['expense', 'transfer_out']);
    }

    // إنشاء معاملة جديدة
    public static function record(
        Wallet $wallet,
        string $type,
        float $amount,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null
    ): self {
        $balanceBefore = $wallet->balance;
        
        // تحديث رصيد المحفظة
        if (in_array($type, ['income', 'transfer_in', 'refund'])) {
            $wallet->increment('balance', $amount);
            $wallet->increment('total_income', $amount);
        } else {
            $wallet->decrement('balance', $amount);
            $wallet->increment('total_expenses', $amount);
        }
        
        return self::create([
            'wallet_id' => $wallet->id,
            'user_id' => $wallet->user_id,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $wallet->fresh()->balance,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
            'notes' => $notes,
            'status' => 'completed',
            'created_by' => auth()->id(),
        ]);
    }
}

