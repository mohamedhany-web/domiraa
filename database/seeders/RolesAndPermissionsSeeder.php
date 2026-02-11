<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Users
            ['name' => 'users.view', 'display_name' => 'عرض المستخدمين', 'group' => 'المستخدمين'],
            ['name' => 'users.create', 'display_name' => 'إنشاء مستخدمين', 'group' => 'المستخدمين'],
            ['name' => 'users.edit', 'display_name' => 'تعديل المستخدمين', 'group' => 'المستخدمين'],
            ['name' => 'users.delete', 'display_name' => 'حذف المستخدمين', 'group' => 'المستخدمين'],
            ['name' => 'users.suspend', 'display_name' => 'إيقاف المستخدمين', 'group' => 'المستخدمين'],
            
            // Properties
            ['name' => 'properties.view', 'display_name' => 'عرض العقارات', 'group' => 'العقارات'],
            ['name' => 'properties.create', 'display_name' => 'إنشاء العقارات', 'group' => 'العقارات'],
            ['name' => 'properties.edit', 'display_name' => 'تعديل العقارات', 'group' => 'العقارات'],
            ['name' => 'properties.delete', 'display_name' => 'حذف العقارات', 'group' => 'العقارات'],
            ['name' => 'properties.approve', 'display_name' => 'الموافقة على العقارات', 'group' => 'العقارات'],
            
            // Bookings
            ['name' => 'bookings.view', 'display_name' => 'عرض الحجوزات', 'group' => 'الحجوزات'],
            ['name' => 'bookings.create', 'display_name' => 'إنشاء الحجوزات', 'group' => 'الحجوزات'],
            ['name' => 'bookings.edit', 'display_name' => 'تعديل الحجوزات', 'group' => 'الحجوزات'],
            ['name' => 'bookings.delete', 'display_name' => 'إلغاء الحجوزات', 'group' => 'الحجوزات'],
            
            // Payments
            ['name' => 'payments.view', 'display_name' => 'عرض المدفوعات', 'group' => 'المدفوعات'],
            ['name' => 'payments.review', 'display_name' => 'مراجعة المدفوعات', 'group' => 'المدفوعات'],
            ['name' => 'payments.refund', 'display_name' => 'استرداد المدفوعات', 'group' => 'المدفوعات'],
            
            // Wallets
            ['name' => 'wallets.view', 'display_name' => 'عرض المحافظ', 'group' => 'المحافظ'],
            ['name' => 'wallets.create', 'display_name' => 'إنشاء المحافظ', 'group' => 'المحافظ'],
            ['name' => 'wallets.edit', 'display_name' => 'تعديل المحافظ', 'group' => 'المحافظ'],
            ['name' => 'wallets.delete', 'display_name' => 'حذف المحافظ', 'group' => 'المحافظ'],
            ['name' => 'wallets.toggle', 'display_name' => 'تفعيل/تعطيل المحافظ', 'group' => 'المحافظ'],
            
            // Inquiries
            ['name' => 'inquiries.view', 'display_name' => 'عرض الاستفسارات', 'group' => 'الاستفسارات'],
            ['name' => 'inquiries.answer', 'display_name' => 'الرد على الاستفسارات', 'group' => 'الاستفسارات'],
            
            // Support Tickets (خدمة العملاء)
            ['name' => 'support.view', 'display_name' => 'عرض تذاكر الدعم', 'group' => 'خدمة العملاء'],
            ['name' => 'support.reply', 'display_name' => 'الرد على تذاكر الدعم', 'group' => 'خدمة العملاء'],
            ['name' => 'support.status', 'display_name' => 'تغيير حالة التذاكر', 'group' => 'خدمة العملاء'],
            ['name' => 'support.priority', 'display_name' => 'تغيير أولوية التذاكر', 'group' => 'خدمة العملاء'],
            ['name' => 'support.delete', 'display_name' => 'حذف تذاكر الدعم', 'group' => 'خدمة العملاء'],
            ['name' => 'support.assign', 'display_name' => 'تعيين التذاكر للموظفين', 'group' => 'خدمة العملاء'],
            
            // Complaints
            ['name' => 'complaints.view', 'display_name' => 'عرض الشكاوى', 'group' => 'الشكاوى'],
            ['name' => 'complaints.manage', 'display_name' => 'إدارة الشكاوى', 'group' => 'الشكاوى'],
            
            // Content
            ['name' => 'content.view', 'display_name' => 'عرض المحتوى', 'group' => 'المحتوى'],
            ['name' => 'content.create', 'display_name' => 'إنشاء المحتوى', 'group' => 'المحتوى'],
            ['name' => 'content.edit', 'display_name' => 'تعديل المحتوى', 'group' => 'المحتوى'],
            ['name' => 'content.delete', 'display_name' => 'حذف المحتوى', 'group' => 'المحتوى'],
            
            // Settings
            ['name' => 'settings.view', 'display_name' => 'عرض الإعدادات', 'group' => 'الإعدادات'],
            ['name' => 'settings.edit', 'display_name' => 'تعديل الإعدادات', 'group' => 'الإعدادات'],
            
            // Roles & Permissions
            ['name' => 'roles.view', 'display_name' => 'عرض الأدوار', 'group' => 'الأدوار والصلاحيات'],
            ['name' => 'roles.create', 'display_name' => 'إنشاء الأدوار', 'group' => 'الأدوار والصلاحيات'],
            ['name' => 'roles.edit', 'display_name' => 'تعديل الأدوار', 'group' => 'الأدوار والصلاحيات'],
            ['name' => 'roles.delete', 'display_name' => 'حذف الأدوار', 'group' => 'الأدوار والصلاحيات'],
            ['name' => 'permissions.view', 'display_name' => 'عرض الصلاحيات', 'group' => 'الأدوار والصلاحيات'],
            ['name' => 'permissions.manage', 'display_name' => 'إدارة الصلاحيات', 'group' => 'الأدوار والصلاحيات'],
            
            // Finance System (النظام المالي)
            ['name' => 'finance.dashboard', 'display_name' => 'عرض لوحة المالية', 'group' => 'النظام المالي'],
            ['name' => 'finance.transactions', 'display_name' => 'عرض المعاملات', 'group' => 'النظام المالي'],
            ['name' => 'finance.transactions.create', 'display_name' => 'إضافة معاملات يدوية', 'group' => 'النظام المالي'],
            ['name' => 'finance.audit', 'display_name' => 'عرض الجرد الشهري', 'group' => 'النظام المالي'],
            ['name' => 'finance.reports', 'display_name' => 'عرض التقارير', 'group' => 'النظام المالي'],
            ['name' => 'finance.reports.create', 'display_name' => 'إنشاء التقارير', 'group' => 'النظام المالي'],
            ['name' => 'finance.reports.delete', 'display_name' => 'حذف التقارير', 'group' => 'النظام المالي'],
            
            // Expenses (المصروفات)
            ['name' => 'expenses.view', 'display_name' => 'عرض المصروفات', 'group' => 'المصروفات'],
            ['name' => 'expenses.create', 'display_name' => 'إضافة مصروفات', 'group' => 'المصروفات'],
            ['name' => 'expenses.edit', 'display_name' => 'تعديل المصروفات', 'group' => 'المصروفات'],
            ['name' => 'expenses.approve', 'display_name' => 'اعتماد المصروفات', 'group' => 'المصروفات'],
            ['name' => 'expenses.pay', 'display_name' => 'دفع المصروفات', 'group' => 'المصروفات'],
            ['name' => 'expenses.delete', 'display_name' => 'حذف المصروفات', 'group' => 'المصروفات'],
            ['name' => 'expenses.categories', 'display_name' => 'إدارة فئات المصروفات', 'group' => 'المصروفات'],
            
            // Activity Logs
            ['name' => 'activity_logs.view', 'display_name' => 'عرض سجل الأنشطة', 'group' => 'سجل الأنشطة'],
            ['name' => 'activity_logs.export', 'display_name' => 'تصدير سجل الأنشطة', 'group' => 'سجل الأنشطة'],
            ['name' => 'activity_logs.clear', 'display_name' => 'حذف سجل الأنشطة القديم', 'group' => 'سجل الأنشطة'],
            
            // Reports
            ['name' => 'reports.view', 'display_name' => 'عرض التقارير', 'group' => 'التقارير'],
            ['name' => 'reports.export', 'display_name' => 'تصدير التقارير', 'group' => 'التقارير'],
            
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'عرض لوحة التحكم', 'group' => 'لوحة التحكم'],
            ['name' => 'dashboard.statistics', 'display_name' => 'عرض الإحصائيات', 'group' => 'لوحة التحكم'],
        ];
        
        foreach ($permissions as $permData) {
            Permission::updateOrCreate(
                ['name' => $permData['name']],
                [
                    'display_name' => $permData['display_name'],
                    'group' => $permData['group'] ?? null,
                ]
            );
        }
        
        // Create Roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'مدير رئيسي',
                'permissions' => Permission::pluck('id')->toArray(), // All permissions
            ],
            [
                'name' => 'admin',
                'display_name' => 'مدير',
                'permissions' => Permission::whereNotIn('name', [
                    'roles.delete', 
                    'activity_logs.clear'
                ])->pluck('id')->toArray(),
            ],
            [
                'name' => 'moderator',
                'display_name' => 'مشرف',
                'permissions' => Permission::whereIn('name', [
                    'users.view',
                    'properties.view',
                    'properties.approve',
                    'bookings.view',
                    'inquiries.view',
                    'inquiries.answer',
                    'complaints.view',
                    'complaints.manage',
                    'support.view',
                    'support.reply',
                    'support.status',
                    'support.priority',
                    'wallets.view',
                    'dashboard.view',
                    'dashboard.statistics',
                ])->pluck('id')->toArray(),
            ],
            [
                'name' => 'support',
                'display_name' => 'دعم فني',
                'permissions' => Permission::whereIn('name', [
                    'users.view',
                    'bookings.view',
                    'inquiries.view',
                    'inquiries.answer',
                    'complaints.view',
                    'complaints.manage',
                    'support.view',
                    'support.reply',
                    'support.status',
                    'support.priority',
                    'dashboard.view',
                ])->pluck('id')->toArray(),
            ],
            [
                'name' => 'owner_basic',
                'display_name' => 'مؤجر (أساسي)',
                'permissions' => Permission::whereIn('name', [
                    'properties.view',
                    'properties.create',
                    'properties.edit',
                    'bookings.view',
                ])->pluck('id')->toArray(),
            ],
            [
                'name' => 'owner_premium',
                'display_name' => 'مؤجر (مميز)',
                'permissions' => Permission::whereIn('name', [
                    'properties.view',
                    'properties.create',
                    'properties.edit',
                    'properties.delete',
                    'bookings.view',
                    'bookings.edit',
                    'reports.view',
                ])->pluck('id')->toArray(),
            ],
            [
                'name' => 'tenant',
                'display_name' => 'مستأجر',
                'permissions' => Permission::whereIn('name', [
                    'properties.view',
                    'bookings.create',
                    'bookings.view',
                ])->pluck('id')->toArray(),
            ],
        ];
        
        foreach ($roles as $roleData) {
            $role = Role::updateOrCreate(
                ['name' => $roleData['name']],
                ['display_name' => $roleData['display_name']]
            );
            
            $role->permissions()->sync($roleData['permissions']);
        }
        
        $this->command->info('Roles and Permissions seeded successfully!');
    }
}

