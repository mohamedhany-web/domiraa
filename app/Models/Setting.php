<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'display_name',
        'description',
    ];

    /**
     * الحصول على قيمة إعداد
     */
    public static function get(string $key, $default = null)
    {
        $setting = Cache::remember("setting_{$key}", 3600, function () use ($key) {
            return static::where('key', $key)->first();
        });

        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * تعيين قيمة إعداد
     */
    public static function set(string $key, $value): void
    {
        $setting = static::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            static::create([
                'key' => $key,
                'value' => $value,
                'type' => is_numeric($value) ? 'number' : (is_bool($value) ? 'boolean' : 'string'),
            ]);
        }

        // مسح الكاش فوراً
        Cache::forget("setting_{$key}");
        Cache::forget('all_settings');
        
        // إذا كان التحديث يتعلق بالأسعار، امسح الكاش بالكامل
        $pricingKeys = [
            'inspection_fee',
            'reservation_percentage_daily',
            'reservation_percentage_monthly',
            'reservation_percentage_yearly',
            'reservation_percentage',
            'platform_fee_percentage',
        ];
        
        if (in_array($key, $pricingKeys)) {
            // مسح كاش جميع مفاتيح الأسعار
            foreach ($pricingKeys as $pricingKey) {
                Cache::forget("setting_{$pricingKey}");
            }
        }
    }

    /**
     * الحصول على جميع الإعدادات
     */
    public static function getAll(): array
    {
        return Cache::remember('all_settings', 3600, function () {
            return static::all()->mapWithKeys(function ($setting) {
                return [$setting->key => static::castValue($setting->value, $setting->type)];
            })->toArray();
        });
    }

    /**
     * الحصول على إعدادات مجموعة معينة
     */
    public static function getByGroup(string $group): array
    {
        return static::where('group', $group)->get()->mapWithKeys(function ($setting) {
            return [$setting->key => static::castValue($setting->value, $setting->type)];
        })->toArray();
    }

    /**
     * تحويل القيمة حسب النوع
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'number' => is_numeric($value) ? (float) $value : 0,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * مسح الكاش
     */
    public static function clearCache(): void
    {
        // مسح الكاش العام
        Cache::forget('all_settings');
        
        // مسح كاش جميع الإعدادات من قاعدة البيانات
        static::withoutGlobalScopes()->get()->each(function ($setting) {
            Cache::forget("setting_{$setting->key}");
        });
        
        // مسح كاش الإعدادات المتعلقة بالأسعار بشكل صريح للتأكد
        $pricingKeys = [
            'inspection_fee',
            'reservation_percentage_daily',
            'reservation_percentage_monthly',
            'reservation_percentage_yearly',
            'reservation_percentage',
            'platform_fee_percentage',
        ];
        
        foreach ($pricingKeys as $key) {
            Cache::forget("setting_{$key}");
        }
    }

    /**
     * الحصول على رسوم المعاينة
     */
    public static function getInspectionFee(): float
    {
        return static::get('inspection_fee', 50);
    }

    /**
     * الحصول على نسبة الحجز
     */
    public static function getReservationPercentage(): float
    {
        return static::get('reservation_percentage', 10);
    }

    /**
     * الحصول على نسبة الحجز حسب نوع السعر
     */
    public static function getReservationPercentageByType(string $priceType): float
    {
        $key = match($priceType) {
            'daily' => 'reservation_percentage_daily',
            'monthly' => 'reservation_percentage_monthly',
            'yearly' => 'reservation_percentage_yearly',
            default => 'reservation_percentage',
        };
        
        return static::get($key, static::getReservationPercentage());
    }

    /**
     * الحصول على نسبة عمولة المنصة
     */
    public static function getPlatformFeePercentage(): float
    {
        return static::get('platform_fee_percentage', 5);
    }

    /**
     * الحصول على رمز العملة
     */
    public static function getCurrencySymbol(): string
    {
        return static::get('currency_symbol', 'ج.م');
    }

    /**
     * مسح كاش الأسعار فقط
     */
    public static function clearPricingCache(): void
    {
        $pricingKeys = [
            'inspection_fee',
            'reservation_percentage_daily',
            'reservation_percentage_monthly',
            'reservation_percentage_yearly',
            'reservation_percentage',
            'platform_fee_percentage',
        ];
        
        foreach ($pricingKeys as $key) {
            Cache::forget("setting_{$key}");
        }
        
        Cache::forget('all_settings');
    }
}

