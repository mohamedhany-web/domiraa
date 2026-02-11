<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['wallet', 'category', 'creator', 'approver']);

        // الفلترة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('wallet')) {
            $query->where('wallet_id', $request->wallet);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('vendor', 'like', '%' . $request->search . '%')
                  ->orWhere('invoice_number', 'like', '%' . $request->search . '%');
            });
        }

        $expenses = $query->latest()->paginate(20);
        
        $categories = ExpenseCategory::active()->ordered()->get();
        $wallets = Wallet::active()->get();

        // الإحصائيات
        $stats = [
            'total' => Expense::count(),
            'pending' => Expense::pending()->count(),
            'approved' => Expense::approved()->count(),
            'paid' => Expense::paid()->count(),
            'total_amount' => Expense::paid()->sum('amount'),
            'this_month' => Expense::paid()
                ->whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->sum('amount'),
        ];

        return view('admin.expenses.index', compact('expenses', 'categories', 'wallets', 'stats'));
    }

    public function create()
    {
        $categories = ExpenseCategory::active()->ordered()->get();
        $wallets = Wallet::active()->get();
        
        return view('admin.expenses.create', compact('categories', 'wallets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'required|exists:expense_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'vendor' => 'nullable|string|max:255',
            'invoice_number' => 'nullable|string|max:100',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'auto_approve' => 'boolean',
        ]);

        // رفع الإيصال
        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $validated['created_by'] = auth()->id();
        
        // اعتماد تلقائي إذا طُلب
        if ($request->boolean('auto_approve')) {
            $validated['status'] = 'approved';
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();
        }

        $expense = Expense::create($validated);

        ActivityLog::log('expense_create', 'تم إنشاء مصروف جديد: ' . $expense->title, $expense);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'تم إضافة المصروف بنجاح');
    }

    public function show(Expense $expense)
    {
        $expense->load(['wallet', 'category', 'creator', 'approver', 'transaction']);
        return view('admin.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if ($expense->isPaid()) {
            return redirect()->route('admin.expenses.index')
                ->with('error', 'لا يمكن تعديل مصروف مدفوع');
        }

        $categories = ExpenseCategory::active()->ordered()->get();
        $wallets = Wallet::active()->get();

        return view('admin.expenses.edit', compact('expense', 'categories', 'wallets'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->isPaid()) {
            return redirect()->route('admin.expenses.index')
                ->with('error', 'لا يمكن تعديل مصروف مدفوع');
        }

        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'required|exists:expense_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'vendor' => 'nullable|string|max:255',
            'invoice_number' => 'nullable|string|max:100',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('receipt')) {
            // حذف الإيصال القديم
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($validated);

        ActivityLog::log('expense_update', 'تم تعديل المصروف: ' . $expense->title, $expense);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'تم تحديث المصروف بنجاح');
    }

    public function approve(Expense $expense)
    {
        if (!$expense->approve()) {
            return back()->with('error', 'لا يمكن اعتماد هذا المصروف');
        }

        ActivityLog::log('expense_approve', 'تم اعتماد المصروف: ' . $expense->title, $expense);

        return back()->with('success', 'تم اعتماد المصروف بنجاح');
    }

    public function reject(Request $request, Expense $expense)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if (!$expense->reject($request->rejection_reason)) {
            return back()->with('error', 'لا يمكن رفض هذا المصروف');
        }

        ActivityLog::log('expense_reject', 'تم رفض المصروف: ' . $expense->title, $expense);

        return back()->with('success', 'تم رفض المصروف');
    }

    public function pay(Expense $expense)
    {
        if (!$expense->pay()) {
            if (!$expense->isApproved()) {
                return back()->with('error', 'يجب اعتماد المصروف قبل الدفع');
            }
            return back()->with('error', 'لا يوجد رصيد كافي في المحفظة');
        }

        ActivityLog::log('expense_pay', 'تم دفع المصروف: ' . $expense->title . ' - المبلغ: ' . $expense->amount, $expense);

        return back()->with('success', 'تم دفع المصروف بنجاح');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->isPaid()) {
            return redirect()->route('admin.expenses.index')
                ->with('error', 'لا يمكن حذف مصروف مدفوع');
        }

        // حذف الإيصال
        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        ActivityLog::log('expense_delete', 'تم حذف المصروف: ' . $expense->title);

        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'تم حذف المصروف بنجاح');
    }

    // فئات المصروفات
    public function categories()
    {
        $categories = ExpenseCategory::withCount('expenses')
            ->withSum(['expenses' => fn($q) => $q->where('status', 'paid')], 'amount')
            ->ordered()
            ->get();
        
        return view('admin.expenses.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        ExpenseCategory::create($validated);

        return back()->with('success', 'تم إضافة الفئة بنجاح');
    }

    public function updateCategory(Request $request, ExpenseCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $category->update($validated);

        return back()->with('success', 'تم تحديث الفئة بنجاح');
    }

    public function destroyCategory(ExpenseCategory $category)
    {
        if ($category->expenses()->exists()) {
            return back()->with('error', 'لا يمكن حذف فئة مرتبطة بمصروفات');
        }

        $category->delete();
        return back()->with('success', 'تم حذف الفئة بنجاح');
    }
}

