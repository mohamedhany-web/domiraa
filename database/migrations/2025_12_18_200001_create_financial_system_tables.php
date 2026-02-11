<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة عمود الرصيد للمحافظ
        if (!Schema::hasColumn('wallets', 'balance')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->decimal('balance', 15, 2)->default(0)->after('is_active');
                $table->decimal('total_income', 15, 2)->default(0)->after('balance');
                $table->decimal('total_expenses', 15, 2)->default(0)->after('total_income');
            });
        }

        // جدول المعاملات المالية
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->enum('type', ['income', 'expense', 'transfer_in', 'transfer_out', 'refund', 'adjustment']);
                $table->decimal('amount', 15, 2);
                $table->decimal('balance_before', 15, 2);
                $table->decimal('balance_after', 15, 2);
                $table->string('reference_type')->nullable(); // payment, expense, booking, etc.
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->string('description');
                $table->text('notes')->nullable();
                $table->string('status')->default('completed'); // pending, completed, cancelled
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
                
                $table->index(['wallet_id', 'created_at']);
                $table->index(['type', 'created_at']);
                $table->index(['reference_type', 'reference_id']);
            });
        }

        // جدول فئات المصروفات (يجب إنشاؤه أولاً)
        if (!Schema::hasTable('expense_categories')) {
            Schema::create('expense_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_en')->nullable();
                $table->string('icon')->nullable();
                $table->string('color')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
            
            // إضافة فئات افتراضية
            DB::table('expense_categories')->insert([
                ['name' => 'رواتب الموظفين', 'name_en' => 'Salaries', 'icon' => 'fa-users', 'color' => '#3B82F6', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'إيجارات', 'name_en' => 'Rent', 'icon' => 'fa-building', 'color' => '#10B981', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'صيانة', 'name_en' => 'Maintenance', 'icon' => 'fa-tools', 'color' => '#F59E0B', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'تسويق وإعلان', 'name_en' => 'Marketing', 'icon' => 'fa-bullhorn', 'color' => '#8B5CF6', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'مستلزمات مكتبية', 'name_en' => 'Office Supplies', 'icon' => 'fa-paperclip', 'color' => '#EC4899', 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'فواتير وخدمات', 'name_en' => 'Utilities', 'icon' => 'fa-bolt', 'color' => '#EF4444', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'سفر ومواصلات', 'name_en' => 'Travel', 'icon' => 'fa-plane', 'color' => '#06B6D4', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'أخرى', 'name_en' => 'Other', 'icon' => 'fa-ellipsis-h', 'color' => '#6B7280', 'sort_order' => 99, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // جدول المصروفات
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
                $table->foreignId('category_id')->nullable()->constrained('expense_categories')->onDelete('set null');
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('amount', 15, 2);
                $table->date('expense_date');
                $table->string('receipt_path')->nullable();
                $table->string('vendor')->nullable();
                $table->string('invoice_number')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
                
                $table->index(['wallet_id', 'expense_date']);
                $table->index(['status', 'created_at']);
            });
        }

        // جدول التقارير المالية المحفوظة
        if (!Schema::hasTable('financial_reports')) {
            Schema::create('financial_reports', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->enum('type', ['monthly', 'quarterly', 'yearly', 'custom']);
                $table->date('start_date');
                $table->date('end_date');
                $table->json('data'); // البيانات المجمعة
                $table->decimal('total_income', 15, 2)->default(0);
                $table->decimal('total_expenses', 15, 2)->default(0);
                $table->decimal('net_profit', 15, 2)->default(0);
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->timestamps();
                
                $table->index(['type', 'start_date', 'end_date']);
            });
        }

        // تحديث جدول المدفوعات لربطه بالمحافظ بشكل أفضل
        if (!Schema::hasColumn('payments', 'target_wallet_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('target_wallet_id')->nullable()->after('wallet_id')->constrained('wallets')->onDelete('set null');
                $table->decimal('platform_fee', 15, 2)->nullable()->after('amount');
                $table->decimal('owner_amount', 15, 2)->nullable()->after('platform_fee');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('expense_categories');
        
        if (Schema::hasColumn('wallets', 'balance')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->dropColumn(['balance', 'total_income', 'total_expenses']);
            });
        }
        
        if (Schema::hasColumn('payments', 'target_wallet_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropForeign(['target_wallet_id']);
                $table->dropColumn(['target_wallet_id', 'platform_fee', 'owner_amount']);
            });
        }
    }
};

