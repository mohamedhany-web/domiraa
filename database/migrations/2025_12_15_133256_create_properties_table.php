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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ownership_proof')->nullable(); // ملف إثبات الملكية
            $table->enum('type', ['residential', 'commercial']); // سكني / تجاري
            $table->string('address'); // العنوان المكتوب
            $table->string('location_lat')->nullable(); // خط العرض
            $table->string('location_lng')->nullable(); // خط الطول
            $table->enum('status', ['furnished', 'unfurnished']); // مفروش / على البلاط
            $table->decimal('price', 10, 2); // السعر
            $table->enum('price_type', ['daily', 'monthly', 'yearly']); // يومي / شهري / سنوي
            $table->integer('contract_duration')->nullable(); // مدة العقد بالسنوات
            $table->decimal('annual_increase', 5, 2)->nullable(); // الزيادة السنوية (%)
            $table->text('video_url')->nullable(); // رابط الفيديو
            $table->text('special_requirements')->nullable(); // اشتراطات خاصة
            $table->json('available_dates')->nullable(); // المواعيد المتاحة للمعاينة
            $table->enum('admin_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
