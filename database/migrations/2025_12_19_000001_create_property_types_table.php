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
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // الاسم بالعربية
            $table->string('slug')->unique(); // المعرف الفريد
            $table->string('icon')->nullable(); // أيقونة (class name)
            $table->integer('sort_order')->default(0); // ترتيب العرض
            $table->boolean('is_active')->default(true); // نشط/غير نشط
            $table->timestamps();
        });
        
        // إضافة البيانات الافتراضية
        DB::table('property_types')->insert([
            ['name' => 'سكني', 'slug' => 'residential', 'icon' => 'fas fa-home', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'تجاري', 'slug' => 'commercial', 'icon' => 'fas fa-store', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'طبي', 'slug' => 'medical', 'icon' => 'fas fa-stethoscope', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_types');
    }
};

