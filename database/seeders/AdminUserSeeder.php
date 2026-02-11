<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء حساب الأدمن
        $admin = User::firstOrCreate(
            ['email' => 'admin@domiraa.com'],
            [
                'name' => 'مدير النظام',
                'phone' => '01000000000',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
        
        // تحديث البيانات إذا كان الحساب موجوداً
        if ($admin->wasRecentlyCreated === false) {
            $admin->update([
                'name' => 'مدير النظام',
                'phone' => '01000000000',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }
        
        $this->command->info('تم إنشاء حساب الأدمن بنجاح!');
        $this->command->info('البريد الإلكتروني: admin@domiraa.com');
        $this->command->info('رقم الهاتف: 01000000000');
        $this->command->info('كلمة المرور: admin123');
    }
}
