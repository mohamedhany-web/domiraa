<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('wallet_id')->nullable()->after('payment_method')->constrained()->onDelete('set null');
            $table->string('receipt_path')->nullable()->after('transaction_id'); // مسار إيصال التحويل
            $table->enum('review_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status'); // حالة المراجعة
            $table->text('review_notes')->nullable()->after('review_status'); // ملاحظات المراجعة
            $table->timestamp('reviewed_at')->nullable()->after('review_notes'); // تاريخ المراجعة
            $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')->constrained('users')->onDelete('set null'); // من قام بالمراجعة
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['wallet_id']);
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['wallet_id', 'receipt_path', 'review_status', 'review_notes', 'reviewed_at', 'reviewed_by']);
        });
    }
};
