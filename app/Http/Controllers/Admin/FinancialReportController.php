<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialReport;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    // لوحة التحكم المالية
    public function dashboard()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $startOfYear = $now->copy()->startOfYear();

        // إحصائيات المحافظ
        $wallets = Wallet::with(['transactions' => function($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        }])->active()->get();

        $totalBalance = $wallets->sum('balance');
        
        // إحصائيات الشهر الحالي
        $monthlyIncome = Transaction::whereIn('type', ['income', 'transfer_in'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        
        $monthlyExpenses = Expense::where('status', 'paid')
            ->whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // إحصائيات السنة
        $yearlyIncome = Transaction::whereIn('type', ['income', 'transfer_in'])
            ->whereBetween('created_at', [$startOfYear, $now])
            ->sum('amount');
        
        $yearlyExpenses = Expense::where('status', 'paid')
            ->whereBetween('expense_date', [$startOfYear, $now])
            ->sum('amount');

        // المصروفات حسب الفئة (هذا الشهر)
        $expensesByCategory = ExpenseCategory::withSum(['expenses' => function($q) use ($startOfMonth, $endOfMonth) {
            $q->where('status', 'paid')->whereBetween('expense_date', [$startOfMonth, $endOfMonth]);
        }], 'amount')->get();

        // الرسم البياني - آخر 12 شهر
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $income = Transaction::whereIn('type', ['income', 'transfer_in'])
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount');
            
            $expenses = Expense::where('status', 'paid')
                ->whereBetween('expense_date', [$start, $end])
                ->sum('amount');

            $chartData[] = [
                'month' => $date->translatedFormat('M Y'),
                'income' => $income,
                'expenses' => $expenses,
                'profit' => $income - $expenses,
            ];
        }

        // آخر المعاملات
        $recentTransactions = Transaction::with('wallet')
            ->latest()
            ->take(10)
            ->get();

        // مصروفات قيد الانتظار
        $pendingExpenses = Expense::with(['category', 'creator'])
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.finance.dashboard', compact(
            'wallets',
            'totalBalance',
            'monthlyIncome',
            'monthlyExpenses',
            'yearlyIncome',
            'yearlyExpenses',
            'expensesByCategory',
            'chartData',
            'recentTransactions',
            'pendingExpenses'
        ));
    }

    // التقارير
    public function reports(Request $request)
    {
        $reports = FinancialReport::with('creator')
            ->latest()
            ->paginate(20);

        return view('admin.finance.reports', compact('reports'));
    }

    // إنشاء تقرير شهري
    public function generateMonthlyReport(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'month' => 'required|integer|min:1|max:12',
        ]);

        $report = FinancialReport::generateMonthlyReport($request->year, $request->month);

        return redirect()->route('admin.finance.report.show', $report)
            ->with('success', 'تم إنشاء التقرير الشهري بنجاح');
    }

    // إنشاء تقرير مخصص
    public function generateCustomReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'title' => 'nullable|string|max:255',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $title = $request->title ?? "تقرير مخصص: {$startDate->format('Y-m-d')} إلى {$endDate->format('Y-m-d')}";

        $report = FinancialReport::generateReport('custom', $startDate, $endDate, $title);

        return redirect()->route('admin.finance.report.show', $report)
            ->with('success', 'تم إنشاء التقرير بنجاح');
    }

    // عرض تقرير
    public function showReport(FinancialReport $report)
    {
        return view('admin.finance.report-show', compact('report'));
    }

    // حذف تقرير
    public function destroyReport(FinancialReport $report)
    {
        $report->delete();
        return redirect()->route('admin.finance.reports')
            ->with('success', 'تم حذف التقرير');
    }

    // الجرد الشهري
    public function monthlyAudit(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // ملخص المحافظ
        $walletsSummary = Wallet::with(['transactions' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        }])->get()->map(function($wallet) {
            return [
                'id' => $wallet->id,
                'name' => $wallet->display_name,
                'type' => $wallet->type_name,
                'current_balance' => $wallet->balance,
                'income' => $wallet->transactions->whereIn('type', ['income', 'transfer_in'])->sum('amount'),
                'expenses' => $wallet->transactions->whereIn('type', ['expense', 'transfer_out'])->sum('amount'),
                'transactions_count' => $wallet->transactions->count(),
            ];
        });

        // ملخص المصروفات حسب الفئة
        $expensesSummary = ExpenseCategory::with(['expenses' => function($q) use ($startDate, $endDate) {
            $q->where('status', 'paid')->whereBetween('expense_date', [$startDate, $endDate]);
        }])->get()->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'icon' => $category->icon,
                'color' => $category->color,
                'count' => $category->expenses->count(),
                'total' => $category->expenses->sum('amount'),
            ];
        })->filter(fn($c) => $c['count'] > 0);

        // ملخص المدفوعات (إيرادات المنصة)
        $paymentsSummary = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as count,
                SUM(amount) as total_amount,
                SUM(platform_fee) as total_fees,
                payment_method
            ')
            ->groupBy('payment_method')
            ->get();

        // إجماليات
        $totals = [
            'total_income' => Transaction::whereIn('type', ['income', 'transfer_in'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount'),
            'total_expenses' => Expense::where('status', 'paid')
                ->whereBetween('expense_date', [$startDate, $endDate])
                ->sum('amount'),
            'total_wallets_balance' => Wallet::sum('balance'),
            'payments_count' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'platform_fees' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('platform_fee'),
        ];

        $totals['net_profit'] = $totals['total_income'] - $totals['total_expenses'];

        // قائمة الأشهر للاختيار
        $months = [];
        for ($i = 0; $i < 24; $i++) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'year' => $date->year,
                'month' => $date->month,
                'label' => $date->translatedFormat('F Y'),
            ];
        }

        return view('admin.finance.monthly-audit', compact(
            'year',
            'month',
            'startDate',
            'endDate',
            'walletsSummary',
            'expensesSummary',
            'paymentsSummary',
            'totals',
            'months'
        ));
    }

    // المعاملات
    public function transactions(Request $request)
    {
        $query = Transaction::with(['wallet', 'user', 'creator']);

        if ($request->filled('wallet')) {
            $query->where('wallet_id', $request->wallet);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(30);
        $wallets = Wallet::all();

        return view('admin.finance.transactions', compact('transactions', 'wallets'));
    }

    // إضافة معاملة يدوية (تعديل رصيد)
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'type' => 'required|in:income,expense,adjustment',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $wallet = Wallet::findOrFail($request->wallet_id);

        Transaction::record(
            $wallet,
            $request->type,
            $request->amount,
            $request->description,
            'manual',
            null,
            $request->notes
        );

        return back()->with('success', 'تم إضافة المعاملة بنجاح');
    }
}

