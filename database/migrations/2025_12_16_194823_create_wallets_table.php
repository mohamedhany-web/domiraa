<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المؤجر
            $table->string('name'); // اسم المحفظة (مثل: بنك مصر، فودافون كاش، إلخ)
            $table->string('bank_name')->nullable(); // اسم البنك
            $table->string('account_number')->nullable(); // رقم الحساب
            $table->string('account_name')->nullable(); // اسم صاحب الحساب
            $table->string('iban')->nullable(); // رقم الآيبان
            $table->string('phone_number')->nullable(); // رقم الهاتف (للمحافظ الإلكترونية)
            $table->enum('type', ['bank', 'mobile_wallet', 'other'])->default('bank'); // نوع المحفظة
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->boolean('is_active')->default(true); // هل المحفظة نشطة
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
