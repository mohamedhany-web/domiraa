<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * العلاقة مع الوحدات
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * الحصول على الأنواع النشطة فقط
     */
    public static function active()
    {
        return static::where('is_active', true)->orderBy('sort_order')->get();
    }

    /**
     * الحصول على جميع الأنواع مرتبة
     */
    public static function ordered()
    {
        return static::orderBy('sort_order')->get();
    }
}

