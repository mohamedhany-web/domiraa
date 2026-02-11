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
        // إضافة حقل للوحدات القابلة للإيجار بالغرفة
        Schema::table('properties', function (Blueprint $table) {
            $table->boolean('is_room_rentable')->default(false)->after('price_type');
            $table->integer('total_rooms')->nullable()->after('is_room_rentable');
        });

        // إنشاء جدول الغرف
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('room_number')->nullable(); // رقم الغرفة
            $table->string('room_name'); // اسم الغرفة (مثل: غرفة 1، غرفة رئيسية)
            $table->text('description')->nullable(); // وصف الغرفة
            $table->decimal('price', 10, 2); // سعر الغرفة
            $table->enum('price_type', ['daily', 'monthly', 'yearly']); // نوع السعر
            $table->integer('area')->nullable(); // المساحة بالمتر المربع
            $table->integer('beds')->default(1); // عدد الأسرة
            $table->json('amenities')->nullable(); // المرافق (مثل: حمام خاص، تلفزيون، إلخ)
            $table->json('images')->nullable(); // صور الغرفة (JSON array)
            $table->boolean('is_available')->default(true); // متاحة/غير متاحة
            $table->timestamps();
        });

        // إنشاء جدول حجوزات الغرف
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('check_in_date');
            $table->date('check_out_date')->nullable();
            $table->enum('booking_type', ['inspection', 'reservation'])->default('reservation');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['pending', 'completed', 'refunded'])->default('pending');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
        Schema::dropIfExists('rooms');
        
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['is_room_rentable', 'total_rooms']);
        });
    }
};

