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
        // إضافة إعدادات الفوتر
        $footerSettings = [
            [
                'key' => 'footer_phone',
                'value' => '01000000000',
                'type' => 'string',
                'group' => 'contact',
                'display_name' => 'رقم الهاتف',
                'description' => 'رقم الهاتف الذي يظهر في الفوتر',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'footer_email',
                'value' => 'info@domiraa.com',
                'type' => 'string',
                'group' => 'contact',
                'display_name' => 'البريد الإلكتروني',
                'description' => 'البريد الإلكتروني الذي يظهر في الفوتر',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'footer_address',
                'value' => 'القاهرة، مصر',
                'type' => 'string',
                'group' => 'contact',
                'display_name' => 'العنوان',
                'description' => 'العنوان الذي يظهر في الفوتر',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'social_facebook',
                'value' => '',
                'type' => 'string',
                'group' => 'contact',
                'display_name' => 'رابط فيسبوك',
                'description' => 'رابط صفحة فيسبوك (اتركه فارغاً لإخفاء الرابط)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'social_twitter',
                'value' => '',
                'type' => 'string',
                'group' => 'contact',
                'display_name' => 'رابط تويتر',
                'description' => 'رابط حساب تويتر (اتركه فارغاً لإخفاء الرابط)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'social_instagram',
                'value' => '',
                'type' => 'string',
                'group' => 'contact',
                'display_name' => 'رابط إنستجرام',
                'description' => 'رابط حساب إنستجرام (اتركه فارغاً لإخفاء الرابط)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'social_linkedin',
                'value' => '',
                'type' => 'string',
                'group' => 'contact',
                'display_name' => 'رابط لينكد إن',
                'description' => 'رابط صفحة لينكد إن (اتركه فارغاً لإخفاء الرابط)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'social_youtube',
                'value' => '',
                'type' => 'string',
                'group' => 'contact',
                'display_name' => 'رابط يوتيوب',
                'description' => 'رابط قناة يوتيوب (اتركه فارغاً لإخفاء الرابط)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($footerSettings as $setting) {
            // التحقق من عدم وجود الإعداد مسبقاً
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('settings')->insert($setting);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'footer_phone',
            'footer_email',
            'footer_address',
            'social_facebook',
            'social_twitter',
            'social_instagram',
            'social_linkedin',
            'social_youtube',
        ])->delete();
    }
};

