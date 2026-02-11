<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'bank_name',
        'account_number',
        'account_name',
        'iban',
        'phone_number',
        'type',
        'notes',
        'is_active',
        'balance',
        'total_income',
        'total_expenses',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'balance' => 'decimal:2',
        'total_income' => 'decimal:2',
        'total_expenses' => 'decimal:2',
    ];

    // العلاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function targetPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'target_wallet_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // الوظائف المساعدة
    public function getDisplayNameAttribute(): string
    {
        if ($this->name) {
            return $this->name;
        }
        
        if ($this->type === 'bank') {
            return $this->bank_name ?? 'حساب بنكي';
        }
        
        return 'محفظة نقدية';
    }

    public function getTypeNameAttribute(): string
    {
        return $this->type === 'bank' ? 'حساب بنكي' : 'محفظة نقدية';
    }

    // إيداع مبلغ
    public function deposit(float $amount, string $description, ?string $referenceType = null, ?int $referenceId = null): Transaction
    {
        return Transaction::record($this, 'income', $amount, $description, $referenceType, $referenceId);
    }

    // سحب مبلغ
    public function withdraw(float $amount, string $description, ?string $referenceType = null, ?int $referenceId = null): ?Transaction
    {
        if ($this->balance < $amount) {
            return null;
        }
        
        return Transaction::record($this, 'expense', $amount, $description, $referenceType, $referenceId);
    }

    // تحويل إلى محفظة أخرى
    public function transferTo(Wallet $targetWallet, float $amount, string $description): bool
    {
        if ($this->balance < $amount) {
            return false;
        }

        // خصم من المحفظة المصدر
        Transaction::record($this, 'transfer_out', $amount, "تحويل إلى: {$targetWallet->display_name}", 'wallet', $targetWallet->id);
        
        // إضافة للمحفظة الهدف
        Transaction::record($targetWallet, 'transfer_in', $amount, "تحويل من: {$this->display_name}", 'wallet', $this->id);

        return true;
    }

    // التحقق من توفر رصيد كافي
    public function hasEnoughBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    // إحصائيات المحفظة
    public function getStats(?\Carbon\Carbon $startDate = null, ?\Carbon\Carbon $endDate = null): array
    {
        $query = $this->transactions();
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $transactions = $query->get();

        return [
            'balance' => $this->balance,
            'total_income' => $transactions->whereIn('type', ['income', 'transfer_in'])->sum('amount'),
            'total_expenses' => $transactions->whereIn('type', ['expense', 'transfer_out'])->sum('amount'),
            'transactions_count' => $transactions->count(),
            'last_transaction' => $transactions->sortByDesc('created_at')->first(),
        ];
    }
}
