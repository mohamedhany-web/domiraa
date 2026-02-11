<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('account_status', ['active', 'suspended', 'banned'])->default('active')->after('role');
            $table->boolean('is_verified')->default(false)->after('account_status'); // توثيق الحساب
            $table->text('violations_history')->nullable()->after('is_verified'); // سجل المخالفات (JSON)
            $table->integer('violations_count')->default(0)->after('violations_history');
            $table->timestamp('suspended_until')->nullable()->after('violations_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_status', 'is_verified', 'violations_history', 'violations_count', 'suspended_until']);
        });
    }
};
