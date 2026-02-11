<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialReport extends Model
{
    protected $fillable = [
        'title',
        'type',
        'start_date',
        'end_date',
        'data',
        'total_income',
        'total_expenses',
        'net_profit',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'data' => 'array',
        'total_income' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'net_profit' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'monthly' => 'شهري',
            'quarterly' => 'ربع سنوي',
            'yearly' => 'سنوي',
            'custom' => 'مخصص',
            default => $this->type,
        };
    }

    // إنشاء تقرير شهري
    public static function generateMonthlyReport(int $year, int $month): self
    {
        $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        return self::generateReport('monthly', $startDate, $endDate, "التقرير الشهري - {$startDate->translatedFormat('F Y')}");
    }

    // إنشاء تقرير مخصص
    public static function generateReport(string $type, $startDate, $endDate, string $title): self
    {
        // جلب البيانات
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->get();

        $payments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        // حساب الإجماليات
        $totalIncome = $transactions->where('type', 'income')->sum('amount') + 
                       $transactions->where('type', 'transfer_in')->sum('amount');
        
        $totalExpenses = $expenses->sum('amount');
        
        $netProfit = $totalIncome - $totalExpenses;

        // تجميع البيانات التفصيلية
        $data = [
            'transactions_count' => $transactions->count(),
            'expenses_count' => $expenses->count(),
            'payments_count' => $payments->count(),
            'income_by_type' => $transactions->whereIn('type', ['income', 'transfer_in'])
                ->groupBy('reference_type')
                ->map(fn($group) => $group->sum('amount')),
            'expenses_by_category' => $expenses->groupBy('category_id')
                ->map(fn($group) => [
                    'count' => $group->count(),
                    'total' => $group->sum('amount'),
                ]),
            'wallets_summary' => Wallet::with(['transactions' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])->get()->map(fn($wallet) => [
                'name' => $wallet->name ?? $wallet->bank_name ?? 'محفظة',
                'balance' => $wallet->balance,
                'income' => $wallet->transactions->whereIn('type', ['income', 'transfer_in'])->sum('amount'),
                'expenses' => $wallet->transactions->whereIn('type', ['expense', 'transfer_out'])->sum('amount'),
            ]),
            'daily_summary' => $transactions->groupBy(fn($t) => $t->created_at->format('Y-m-d'))
                ->map(fn($group) => [
                    'income' => $group->whereIn('type', ['income', 'transfer_in'])->sum('amount'),
                    'expenses' => $group->whereIn('type', ['expense', 'transfer_out'])->sum('amount'),
                ]),
        ];

        return self::create([
            'title' => $title,
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $data,
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'created_by' => auth()->id(),
        ]);
    }
}

