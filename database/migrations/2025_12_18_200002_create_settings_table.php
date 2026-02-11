<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string'); // string, number, boolean, json
                $table->string('group')->default('general');
                $table->string('display_name')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
        
        // إضافة الإعدادات الافتراضية
        $settings = [
            [
                'key' => 'inspection_fee',
                'value' => '50',
                'type' => 'number',
                'group' => 'pricing',
                'display_name' => 'رسوم المعاينة',
                'description' => 'المبلغ المطلوب لحجز معاينة الوحدة',
            ],
            [
                'key' => 'reservation_percentage',
                'value' => '10',
                'type' => 'number',
                'group' => 'pricing',
                'display_name' => 'نسبة الحجز',
                'description' => 'نسبة من سعر الوحدة كرسوم حجز',
            ],
            [
                'key' => 'platform_fee_percentage',
                'value' => '5',
                'type' => 'number',
                'group' => 'pricing',
                'display_name' => 'عمولة المنصة',
                'description' => 'نسبة عمولة المنصة من كل عملية',
            ],
            [
                'key' => 'currency',
                'value' => 'EGP',
                'type' => 'string',
                'group' => 'general',
                'display_name' => 'العملة',
                'description' => 'العملة المستخدمة في المنصة',
            ],
            [
                'key' => 'currency_symbol',
                'value' => 'ج.م',
                'type' => 'string',
                'group' => 'general',
                'display_name' => 'رمز العملة',
                'description' => 'رمز العملة المعروض',
            ],
            [
                'key' => 'site_name',
                'value' => 'دوميرا',
                'type' => 'string',
                'group' => 'general',
                'display_name' => 'اسم الموقع',
                'description' => 'اسم المنصة',
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@domiraa.com',
                'type' => 'string',
                'group' => 'contact',
                'display_name' => 'البريد الإلكتروني',
                'description' => 'البريد الإلكتروني للتواصل',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+20 123 456 789',
                'type' => 'string',
                'group' => 'contact',
                'display_name' => 'رقم الهاتف',
                'description' => 'رقم الهاتف للتواصل',
            ],
        ];
        
        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

