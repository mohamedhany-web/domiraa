<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Expense extends Model
{
    protected $fillable = [
        'wallet_id',
        'category_id',
        'title',
        'description',
        'amount',
        'expense_date',
        'receipt_path',
        'vendor',
        'invoice_number',
        'status',
        'approved_by',
        'approved_at',
        'created_by',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // العلاقات
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'reference_id')
            ->where('reference_type', 'expense');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByWallet($query, $walletId)
    {
        return $query->where('wallet_id', $walletId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    // الوظائف المساعدة
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
            'paid' => 'مدفوع',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'blue',
            'rejected' => 'red',
            'paid' => 'green',
            default => 'gray',
        };
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    // اعتماد المصروف
    public function approve(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return true;
    }

    // رفض المصروف
    public function reject(string $reason): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);

        return true;
    }

    // دفع المصروف
    public function pay(): bool
    {
        if (!$this->isApproved()) {
            return false;
        }

        // التحقق من وجود رصيد كافي
        if ($this->wallet->balance < $this->amount) {
            return false;
        }

        // إنشاء المعاملة المالية
        Transaction::record(
            $this->wallet,
            'expense',
            $this->amount,
            $this->title,
            'expense',
            $this->id,
            $this->description
        );

        $this->update(['status' => 'paid']);

        return true;
    }

    // رقم المصروف
    public function getCodeAttribute(): string
    {
        return 'EXP-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // تاريخ الرفض (يعيد approved_at إذا كانت الحالة rejected)
    public function getRejectedAtAttribute()
    {
        return $this->status === 'rejected' ? $this->approved_at : null;
    }
}

