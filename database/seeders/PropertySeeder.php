<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مؤجر تجريبي إذا لم يكن موجوداً
        $owner = User::firstOrCreate(
            ['email' => 'owner@domiraa.com'],
            [
                'name' => 'مؤجر تجريبي',
                'phone' => '01012345678',
                'password' => Hash::make('owner123'),
                'role' => 'owner',
                'is_verified' => true,
            ]
        );

        // بيانات الوحدات التجريبية
        $properties = [
            [
                'type' => 'residential',
                'address' => 'القاهرة الجديدة، الحي الأول، شارع النصر، عمارة 15',
                'location_lat' => '30.0444',
                'location_lng' => '31.2357',
                'status' => 'furnished',
                'price' => 5000,
                'price_type' => 'monthly',
                'contract_duration' => 1,
                'annual_increase' => 5,
                'area' => 120,
                'rooms' => 3,
                'bathrooms' => 2,
                'floor' => 'الثالث',
                'amenities' => ['gas', 'electricity', 'water', 'internet', 'elevator'],
                'special_requirements' => 'ممنوع التدخين، مطلوب ضمان شهر',
            ],
            [
                'type' => 'residential',
                'address' => 'مدينة نصر، شارع عباس العقاد، برج الأندلس',
                'location_lat' => '30.0626',
                'location_lng' => '31.3197',
                'status' => 'unfurnished',
                'price' => 8000,
                'price_type' => 'monthly',
                'contract_duration' => 2,
                'annual_increase' => 7,
                'area' => 150,
                'rooms' => 4,
                'bathrooms' => 3,
                'floor' => 'السادس',
                'amenities' => ['gas', 'electricity', 'water', 'internet', 'elevator', 'parking'],
                'special_requirements' => 'مطلوب عائلة فقط',
            ],
            [
                'type' => 'residential',
                'address' => 'المعادي، كورنيش النيل، عمارة النور',
                'location_lat' => '29.9602',
                'location_lng' => '31.2620',
                'status' => 'furnished',
                'price' => 12000,
                'price_type' => 'monthly',
                'contract_duration' => 1,
                'annual_increase' => 10,
                'area' => 180,
                'rooms' => 5,
                'bathrooms' => 4,
                'floor' => 'الأول',
                'amenities' => ['gas', 'electricity', 'water', 'internet', 'elevator', 'parking'],
                'special_requirements' => 'شقة راقية مع إطلالة على النيل',
            ],
            [
                'type' => 'commercial',
                'address' => 'مصر الجديدة، شارع العروبة، محل تجاري',
                'location_lat' => '30.0875',
                'location_lng' => '31.3244',
                'status' => 'unfurnished',
                'price' => 15000,
                'price_type' => 'monthly',
                'contract_duration' => 3,
                'annual_increase' => 8,
                'area' => 80,
                'rooms' => 1,
                'bathrooms' => 1,
                'floor' => 'الأرضي',
                'amenities' => ['electricity', 'water', 'internet'],
                'special_requirements' => 'مناسب للمحلات التجارية والمكاتب',
            ],
            [
                'type' => 'residential',
                'address' => 'زهراء مدينة نصر، شارع حسن المأمون',
                'location_lat' => '30.0725',
                'location_lng' => '31.3186',
                'status' => 'furnished',
                'price' => 6000,
                'price_type' => 'monthly',
                'contract_duration' => 1,
                'annual_increase' => 6,
                'area' => 100,
                'rooms' => 2,
                'bathrooms' => 1,
                'floor' => 'الثاني',
                'amenities' => ['gas', 'electricity', 'water', 'internet'],
                'special_requirements' => 'شقة هادئة ومناسبة للعزاب',
            ],
            [
                'type' => 'residential',
                'address' => 'المهندسين، شارع جامعة الدول العربية',
                'location_lat' => '30.0525',
                'location_lng' => '31.2001',
                'status' => 'furnished',
                'price' => 10000,
                'price_type' => 'monthly',
                'contract_duration' => 2,
                'annual_increase' => 8,
                'area' => 140,
                'rooms' => 3,
                'bathrooms' => 2,
                'floor' => 'الرابع',
                'amenities' => ['gas', 'electricity', 'water', 'internet', 'elevator'],
                'special_requirements' => 'قريبة من المترو والخدمات',
            ],
            [
                'type' => 'residential',
                'address' => 'الزمالك، شارع حسن صبري، عمارة الأهرام',
                'location_lat' => '30.0626',
                'location_lng' => '31.2197',
                'status' => 'furnished',
                'price' => 20000,
                'price_type' => 'monthly',
                'contract_duration' => 1,
                'annual_increase' => 12,
                'area' => 200,
                'rooms' => 4,
                'bathrooms' => 3,
                'floor' => 'السابع',
                'amenities' => ['gas', 'electricity', 'water', 'internet', 'elevator', 'parking'],
                'special_requirements' => 'شقة فاخرة مع إطلالة على النيل',
            ],
            [
                'type' => 'residential',
                'address' => 'شبرا، شارع رمسيس، عمارة السلام',
                'location_lat' => '30.0775',
                'location_lng' => '31.2401',
                'status' => 'unfurnished',
                'price' => 3500,
                'price_type' => 'monthly',
                'contract_duration' => 1,
                'annual_increase' => 5,
                'area' => 90,
                'rooms' => 2,
                'bathrooms' => 1,
                'floor' => 'الأول',
                'amenities' => ['gas', 'electricity', 'water'],
                'special_requirements' => 'شقة اقتصادية ومناسبة للعائلات',
            ],
            [
                'type' => 'commercial',
                'address' => 'المعادي، شارع 9، مكتب تجاري',
                'location_lat' => '29.9602',
                'location_lng' => '31.2620',
                'status' => 'unfurnished',
                'price' => 8000,
                'price_type' => 'monthly',
                'contract_duration' => 2,
                'annual_increase' => 7,
                'area' => 60,
                'rooms' => 1,
                'bathrooms' => 1,
                'floor' => 'الأول',
                'amenities' => ['electricity', 'water', 'internet'],
                'special_requirements' => 'مناسب للمكاتب والاستشارات',
            ],
            [
                'type' => 'residential',
                'address' => 'العباسية، شارع رمسيس، عمارة النور',
                'location_lat' => '30.0626',
                'location_lng' => '31.2801',
                'status' => 'furnished',
                'price' => 4500,
                'price_type' => 'monthly',
                'contract_duration' => 1,
                'annual_increase' => 6,
                'area' => 110,
                'rooms' => 3,
                'bathrooms' => 2,
                'floor' => 'الثالث',
                'amenities' => ['gas', 'electricity', 'water', 'internet'],
                'special_requirements' => 'شقة نظيفة ومريحة',
            ],
            [
                'type' => 'residential',
                'address' => 'مدينة 6 أكتوبر، الحي السادس، عمارة الأمل',
                'location_lat' => '29.9689',
                'location_lng' => '30.9500',
                'status' => 'furnished',
                'price' => 5500,
                'price_type' => 'monthly',
                'contract_duration' => 1,
                'annual_increase' => 5,
                'area' => 130,
                'rooms' => 3,
                'bathrooms' => 2,
                'floor' => 'الخامس',
                'amenities' => ['gas', 'electricity', 'water', 'internet', 'elevator', 'parking'],
                'special_requirements' => 'شقة في مجمع سكني هادئ',
            ],
            [
                'type' => 'residential',
                'address' => 'المنيل، شارع الكورنيش، عمارة النيل',
                'location_lat' => '30.0194',
                'location_lng' => '31.2275',
                'status' => 'furnished',
                'price' => 15000,
                'price_type' => 'monthly',
                'contract_duration' => 2,
                'annual_increase' => 10,
                'area' => 170,
                'rooms' => 4,
                'bathrooms' => 3,
                'floor' => 'الثاني',
                'amenities' => ['gas', 'electricity', 'water', 'internet', 'elevator', 'parking'],
                'special_requirements' => 'شقة راقية مع إطلالة على النيل',
            ],
        ];

        foreach ($properties as $index => $propertyData) {
            $property = Property::create([
                'user_id' => $owner->id,
                'ownership_proof' => 'ownership_proofs/demo_' . ($index + 1) . '.pdf',
                'type' => $propertyData['type'],
                'address' => $propertyData['address'],
                'location_lat' => $propertyData['location_lat'],
                'location_lng' => $propertyData['location_lng'],
                'status' => $propertyData['status'],
                'price' => $propertyData['price'],
                'price_type' => $propertyData['price_type'],
                'contract_duration' => $propertyData['contract_duration'],
                'annual_increase' => $propertyData['annual_increase'],
                'area' => $propertyData['area'],
                'rooms' => $propertyData['rooms'],
                'bathrooms' => $propertyData['bathrooms'],
                'floor' => $propertyData['floor'],
                'amenities' => $propertyData['amenities'],
                'special_requirements' => $propertyData['special_requirements'],
                'admin_status' => 'approved',
                'views_count' => rand(10, 500),
            ]);

            // إضافة صور تجريبية من الإنترنت (Unsplash)
            $imageUrls = [
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1560448075-cbc16bf4d33d?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1560449752-95d6b5e0c0e0?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1560448204-61dc36dc5c4e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1560448075-0eefc5e58b9c?w=800&h=600&fit=crop',
            ];
            
            // اختيار صور مختلفة لكل وحدة
            $selectedImages = array_slice($imageUrls, ($index * 3) % count($imageUrls), 3);
            if (count($selectedImages) < 3) {
                $selectedImages = array_merge($selectedImages, array_slice($imageUrls, 0, 3 - count($selectedImages)));
            }
            
            for ($i = 0; $i < 3; $i++) {
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $selectedImages[$i], // حفظ رابط الصورة مباشرة
                    'order' => $i,
                ]);
            }
        }

        $this->command->info('تم إنشاء ' . count($properties) . ' وحدة تجريبية بنجاح!');
    }
}
