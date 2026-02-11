<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\PhoneAuthController;
use App\Http\Controllers\Owner\PropertyController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\AccountController;
use App\Http\Controllers\Owner\InspectionController;
use App\Http\Controllers\Owner\PaymentController;
use App\Http\Controllers\Owner\MessageController;
use App\Http\Controllers\Owner\RatingController;
use App\Http\Controllers\Owner\SupportController;
use App\Http\Controllers\Owner\SettingsController;
use App\Http\Controllers\Tenant\SearchController;
use App\Http\Controllers\Tenant\BookingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Route للوصول إلى الصور المخزنة (حل بديل إذا لم يعمل symlink)
// يجب أن يكون هذا Route قبل أي routes أخرى
// Route للوصول إلى الملفات (صور، PDF، إلخ)
Route::get('/storage/{path}', function ($path) {
    try {
        // Clean path to prevent directory traversal
        $path = str_replace('..', '', $path);
        $path = ltrim($path, '/');
        
        // Build file path
        $basePath = storage_path('app/public');
        $filePath = $basePath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        
        // Normalize path separators
        $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath);
        
        // Log the request for debugging
        \Log::info('Storage route accessed', [
            'requested_path' => $path,
            'file_path' => $filePath,
            'file_exists' => @file_exists($filePath),
            'is_file' => @is_file($filePath),
            'storage_path' => $basePath,
        ]);
    
        // Check if file exists
        if (!@file_exists($filePath)) {
            \Log::warning('Storage file not found', [
                'requested_path' => $path,
                'file_path' => $filePath,
            ]);
            abort(404, 'File not found');
        }
        
        if (!@is_file($filePath)) {
            \Log::warning('Storage path is not a file', [
                'requested_path' => $path,
                'file_path' => $filePath,
            ]);
            abort(404, 'Not a file');
        }
        
        // Get real path for security check (use filePath if realpath fails)
        $realPath = @realpath($filePath) ?: $filePath;
        $allowedPath = @realpath($basePath) ?: $basePath;
        
        // Security check: ensure the file is within storage/app/public
        // Normalize paths for comparison
        $normalizedRealPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $realPath);
        $normalizedAllowedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $allowedPath);
        
        if (strpos($normalizedRealPath, $normalizedAllowedPath) !== 0) {
            \Log::warning('Storage access denied - path outside allowed directory', [
                'requested_path' => $path,
                'file_path' => $filePath,
                'real_path' => $realPath,
                'allowed_path' => $allowedPath,
            ]);
            abort(404, 'Access denied');
        }
        
        // Check if file is readable
        if (!@is_readable($realPath)) {
            \Log::warning('Storage file is not readable', [
                'requested_path' => $path,
                'real_path' => $realPath,
            ]);
            abort(403, 'File not readable');
        }
        
        // Get mime type
        $mimeType = @mime_content_type($realPath);
        if (!$mimeType) {
            $extension = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml',
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];
            $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        }
        
        // Set proper headers
        $headers = [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ];
        
        // For PDF files, add inline display
        if ($mimeType === 'application/pdf') {
            $headers['Content-Disposition'] = 'inline; filename="' . basename($realPath) . '"';
        }
        
        \Log::info('Storage file served successfully', [
            'requested_path' => $path,
            'real_path' => $realPath,
            'mime_type' => $mimeType,
        ]);
        
        return response()->file($realPath, $headers);
    } catch (\Exception $e) {
        \Log::error('Storage route error', [
            'path' => $path ?? 'unknown',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        abort(404, 'File not found');
    }
})->where('path', '.*')->name('storage.file')->middleware('web');

// CSRF Token endpoint (for AJAX requests)
Route::get('/csrf-token', [\App\Http\Controllers\CsrfTokenController::class, 'getToken'])->name('csrf.token');

// الصفحة الرئيسية
Route::get('/', [SearchController::class, 'index'])->name('home');

// المصادقة - محمية بـ Rate Limiting
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/login', [PhoneAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PhoneAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [PhoneAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [PhoneAuthController::class, 'register'])->name('register.post');
});
Route::post('/logout', [PhoneAuthController::class, 'logout'])->name('logout');

// البحث والعرض (للمستأجرين والزوار)
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/ajax', [SearchController::class, 'ajaxSearch'])->name('search.ajax');
Route::get('/properties', [SearchController::class, 'index'])->name('properties');
Route::get('/property/{property}', [SearchController::class, 'show'])->name('property.show');
Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');

// الدعم الفني (Chat Widget)
Route::prefix('support-chat')->name('support.chat.')->group(function () {
    Route::get('/tickets', [\App\Http\Controllers\SupportChatController::class, 'getTickets'])->name('tickets');
    Route::post('/tickets', [\App\Http\Controllers\SupportChatController::class, 'createTicket'])->name('create');
    Route::get('/tickets/{ticket}/messages', [\App\Http\Controllers\SupportChatController::class, 'getMessages'])->name('messages');
    Route::post('/tickets/{ticket}/messages', [\App\Http\Controllers\SupportChatController::class, 'sendMessage'])->name('send');
    Route::get('/tickets/{ticket}/poll', [\App\Http\Controllers\SupportChatController::class, 'pollMessages'])->name('poll');
});

// حجز المعاينة (للزوار بدون تسجيل دخول)
Route::get('/property/{property}/inspection', [BookingController::class, 'createGuest'])->name('inspection.create');
Route::post('/property/{property}/inspection', [BookingController::class, 'storeGuest'])->name('inspection.store');

// تتبع حالة الطلب (للزوار بدون تسجيل دخول)
Route::get('/booking/tracking', [\App\Http\Controllers\Tenant\BookingTrackingController::class, 'index'])->name('booking.tracking');
Route::post('/booking/track', [\App\Http\Controllers\Tenant\BookingTrackingController::class, 'track'])->name('booking.track');

// حجز الغرف
Route::get('/property/{property}/room/{room}/booking', [BookingController::class, 'createRoomBooking'])->name('room.booking.create');
Route::post('/property/{property}/room/{room}/booking', [BookingController::class, 'storeRoomBooking'])->name('room.booking.store');

// لوحة التحكم
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // المؤجر
    Route::prefix('owner')->name('owner.')->middleware('role:owner')->group(function () {
        // Dashboard
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        
        // Properties
        Route::resource('properties', PropertyController::class);
        Route::get('/properties/{property}/ownership-proof/download', [PropertyController::class, 'downloadOwnershipProof'])->name('properties.ownership-proof.download');
        
        // Account
        Route::get('/account', [AccountController::class, 'index'])->name('account');
        Route::put('/account', [AccountController::class, 'update'])->name('account.update');
        Route::post('/account/upload-document', [AccountController::class, 'uploadDocument'])->name('account.upload-document');
        Route::post('/account/change-password', [AccountController::class, 'changePassword'])->name('account.change-password');
        
        // Inspections
        Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections');
        Route::post('/inspections/{booking}/accept', [InspectionController::class, 'accept'])->name('inspections.accept');
        Route::post('/inspections/{booking}/reject', [InspectionController::class, 'reject'])->name('inspections.reject');
        Route::post('/inspections/{booking}/suggest-alternative', [InspectionController::class, 'suggestAlternative'])->name('inspections.suggest-alternative');
        
        // Bookings
        Route::get('/bookings', [PaymentController::class, 'bookings'])->name('bookings');
        Route::post('/bookings/{booking}/upload-contract', [PaymentController::class, 'uploadContract'])->name('bookings.upload-contract');
        Route::post('/bookings/{booking}/confirm-payment', [PaymentController::class, 'confirmPayment'])->name('bookings.confirm-payment');
        
        // Payments - Removed from owner account
        // Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
        // Route::get('/payments/reports', [PaymentController::class, 'reports'])->name('payments.reports');
        
        // Wallets (المحافظ)
        Route::get('/wallets', [PaymentController::class, 'wallets'])->name('wallets');
        Route::post('/wallets', [PaymentController::class, 'storeWallet'])->name('wallets.store');
        Route::put('/wallets/{wallet}', [PaymentController::class, 'updateWallet'])->name('wallets.update');
        Route::delete('/wallets/{wallet}', [PaymentController::class, 'deleteWallet'])->name('wallets.delete');
        
        // Messages
        Route::get('/messages', [MessageController::class, 'index'])->name('messages');
        Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
        Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
        
        // Ratings
        Route::get('/ratings', [RatingController::class, 'index'])->name('ratings');
        Route::post('/ratings/{rating}/reply', [RatingController::class, 'reply'])->name('ratings.reply');
        
        // Support
        Route::get('/support', [SupportController::class, 'index'])->name('support');
        Route::post('/support', [SupportController::class, 'store'])->name('support.store');
        Route::get('/support/{complaint}', [SupportController::class, 'show'])->name('support.show');
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });

    // المستأجر
    Route::prefix('tenant')->name('tenant.')->middleware('role:tenant')->group(function () {
        Route::get('/property/{property}/booking', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/property/{property}/booking', [BookingController::class, 'store'])->name('booking.store');
        
        // الاستفسارات
        Route::get('/inquiries', [\App\Http\Controllers\Tenant\InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('/inquiries/{inquiry}', [\App\Http\Controllers\Tenant\InquiryController::class, 'show'])->name('inquiries.show');
    });
    
    // صفحات الدفع (متاحة لجميع المستخدمين المسجلين)
    Route::middleware('auth')->group(function () {
        Route::get('/tenant/booking/{booking}/payment', [\App\Http\Controllers\Tenant\BookingController::class, 'payment'])->name('tenant.booking.payment');
        Route::post('/tenant/booking/{booking}/confirm-payment', [\App\Http\Controllers\Tenant\BookingController::class, 'confirmPayment'])->name('tenant.booking.confirm-payment');
    });

    // الأدمن
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware('permission:dashboard.view')->name('dashboard');
        Route::get('/properties', [AdminController::class, 'properties'])->middleware('permission:properties.view')->name('properties');
        Route::get('/properties/create', [AdminController::class, 'createProperty'])->middleware('permission:properties.create')->name('properties.create');
        Route::post('/properties', [AdminController::class, 'storeProperty'])->middleware('permission:properties.create')->name('properties.store');
        Route::get('/properties/{property}/review', [AdminController::class, 'reviewProperty'])->middleware('permission:properties.approve')->name('properties.review');
        Route::post('/properties/{property}/approve', [AdminController::class, 'approveProperty'])->middleware('permission:properties.approve')->name('properties.approve');
        Route::post('/properties/{property}/reject', [AdminController::class, 'rejectProperty'])->middleware('permission:properties.approve')->name('properties.reject');
        Route::get('/users', [AdminController::class, 'users'])->middleware('permission:users.view')->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->middleware('permission:users.create')->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->middleware('permission:users.create')->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->middleware('permission:users.edit')->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->middleware('permission:users.edit')->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->middleware('permission:users.delete')->name('users.destroy');
        Route::get('/bookings', [AdminController::class, 'bookings'])->middleware('permission:bookings.view')->name('bookings');
        Route::get('/bookings/create', [AdminController::class, 'createBooking'])->middleware('permission:bookings.create')->name('bookings.create');
        Route::post('/bookings', [AdminController::class, 'storeBooking'])->middleware('permission:bookings.create')->name('bookings.store');
        Route::get('/bookings/{booking}/edit', [AdminController::class, 'editBooking'])->middleware('permission:bookings.edit')->name('bookings.edit');
        Route::put('/bookings/{booking}', [AdminController::class, 'updateBooking'])->middleware('permission:bookings.edit')->name('bookings.update');
        Route::delete('/bookings/{booking}', [AdminController::class, 'destroyBooking'])->middleware('permission:bookings.delete')->name('bookings.destroy');
        Route::post('/inspections/{booking}/approve', [AdminController::class, 'approveInspection'])->middleware('permission:bookings.manage')->name('inspections.approve');
        Route::post('/inspections/{booking}/reject', [AdminController::class, 'rejectInspection'])->middleware('permission:bookings.manage')->name('inspections.reject');
        Route::get('/inquiries', [AdminController::class, 'inquiries'])->middleware('permission:inquiries.view')->name('inquiries');
        Route::post('/inquiries/{inquiry}/answer', [AdminController::class, 'answerInquiry'])->middleware('permission:inquiries.answer')->name('inquiries.answer');
        Route::get('/properties/{property}/edit', [AdminController::class, 'editProperty'])->middleware('permission:properties.edit')->name('properties.edit');
        Route::put('/properties/{property}', [AdminController::class, 'updateProperty'])->middleware('permission:properties.edit')->name('properties.update');
        Route::delete('/properties/{property}', [AdminController::class, 'destroyProperty'])->middleware('permission:properties.delete')->name('properties.destroy');
        Route::get('/settings', [AdminController::class, 'settings'])->middleware('permission:settings.view')->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->middleware('permission:settings.edit')->name('settings.update');
        
        // المدفوعات
        Route::get('/payments', [AdminController::class, 'payments'])->middleware('permission:payments.view')->name('payments');
        Route::post('/payments/{payment}/review', [AdminController::class, 'reviewPayment'])->middleware('permission:payments.review')->name('payments.review');
        Route::post('/payments/{payment}/refund', [AdminController::class, 'refundPayment'])->middleware('permission:payments.refund')->name('payments.refund');
        
        // المحافظ
        Route::get('/wallets', [AdminController::class, 'wallets'])->middleware('permission:wallets.view')->name('wallets');
        Route::get('/wallets/create', [AdminController::class, 'createWallet'])->middleware('permission:wallets.create')->name('wallets.create');
        Route::post('/wallets', [AdminController::class, 'storeWallet'])->middleware('permission:wallets.create')->name('wallets.store');
        Route::get('/wallets/{wallet}', [AdminController::class, 'showWallet'])->middleware('permission:wallets.view')->name('wallets.show');
        Route::get('/wallets/{wallet}/edit', [AdminController::class, 'editWallet'])->middleware('permission:wallets.edit')->name('wallets.edit');
        Route::put('/wallets/{wallet}', [AdminController::class, 'updateWallet'])->middleware('permission:wallets.edit')->name('wallets.update');
        Route::post('/wallets/{wallet}/toggle', [AdminController::class, 'toggleWalletStatus'])->middleware('permission:wallets.toggle')->name('wallets.toggle');
        Route::delete('/wallets/{wallet}', [AdminController::class, 'destroyWallet'])->middleware('permission:wallets.delete')->name('wallets.destroy');
        
        // الشكاوى
        Route::get('/complaints', [AdminController::class, 'complaints'])->middleware('permission:complaints.view')->name('complaints');
        Route::get('/complaints/{complaint}', [AdminController::class, 'showComplaint'])->middleware('permission:complaints.view')->name('complaints.show');
        Route::put('/complaints/{complaint}', [AdminController::class, 'updateComplaint'])->middleware('permission:complaints.manage')->name('complaints.update');
        
        // إدارة المحتوى
        Route::get('/content', [AdminController::class, 'content'])->middleware('permission:content.view')->name('content');
        Route::get('/content/create', [AdminController::class, 'createContent'])->middleware('permission:content.create')->name('content.create');
        Route::post('/content', [AdminController::class, 'storeContent'])->middleware('permission:content.create')->name('content.store');
        Route::get('/content/{contentPage}/edit', [AdminController::class, 'editContent'])->middleware('permission:content.edit')->name('content.edit');
        Route::put('/content/{contentPage}', [AdminController::class, 'updateContent'])->middleware('permission:content.edit')->name('content.update');
        Route::delete('/content/{contentPage}', [AdminController::class, 'destroyContent'])->middleware('permission:content.delete')->name('content.destroy');
        
        // الإعدادات
        Route::prefix('settings')->name('settings.')->middleware('permission:settings.view')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->middleware('permission:settings.edit')->name('update');
            Route::get('/pricing', [\App\Http\Controllers\Admin\SettingsController::class, 'pricing'])->name('pricing');
            Route::post('/pricing', [\App\Http\Controllers\Admin\SettingsController::class, 'updatePricing'])->middleware('permission:settings.edit')->name('pricing.update');
        });
        
        // إدارة أنواع الوحدة
        Route::prefix('property-types')->name('property-types.')->middleware('permission:settings.edit')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PropertyTypeController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\PropertyTypeController::class, 'store'])->name('store');
            Route::put('/{propertyType}', [\App\Http\Controllers\Admin\PropertyTypeController::class, 'update'])->name('update');
            Route::delete('/{propertyType}', [\App\Http\Controllers\Admin\PropertyTypeController::class, 'destroy'])->name('destroy');
        });
        
        // إدارة الأدوار والصلاحيات
        Route::middleware('permission:roles.view')->group(function () {
            Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/roles/create', [RoleController::class, 'create'])->middleware('permission:roles.create')->name('roles.create');
            Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:roles.create')->name('roles.store');
            Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
            Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:roles.edit')->name('roles.edit');
            Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:roles.edit')->name('roles.update');
            Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles.delete')->name('roles.destroy');
        });
        
        Route::middleware('permission:permissions.view')->group(function () {
            Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
            Route::get('/permissions/create', [PermissionController::class, 'create'])->middleware('permission:permissions.manage')->name('permissions.create');
            Route::post('/permissions', [PermissionController::class, 'store'])->middleware('permission:permissions.manage')->name('permissions.store');
            Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->middleware('permission:permissions.manage')->name('permissions.edit');
            Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->middleware('permission:permissions.manage')->name('permissions.update');
            Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:permissions.manage')->name('permissions.destroy');
        });
        
        // إدارة صلاحيات المستخدمين
        Route::get('/users-permissions', [UserPermissionController::class, 'index'])->middleware('permission:users.view')->name('users-permissions.index');
        Route::get('/users-permissions/create', [UserPermissionController::class, 'create'])->middleware('permission:users.create')->name('users-permissions.create');
        Route::post('/users-permissions', [UserPermissionController::class, 'store'])->middleware('permission:users.create')->name('users-permissions.store');
        Route::get('/users-permissions/{user}', [UserPermissionController::class, 'show'])->middleware('permission:users.view')->name('users-permissions.show');
        Route::get('/users-permissions/{user}/edit', [UserPermissionController::class, 'edit'])->middleware('permission:users.edit')->name('users-permissions.edit');
        Route::put('/users-permissions/{user}', [UserPermissionController::class, 'update'])->middleware('permission:users.edit')->name('users-permissions.update');
        Route::delete('/users-permissions/{user}', [UserPermissionController::class, 'destroy'])->middleware('permission:users.delete')->name('users-permissions.destroy');
        Route::post('/users-permissions/{user}/suspend', [UserPermissionController::class, 'suspend'])->middleware('permission:users.suspend')->name('users-permissions.suspend');
        Route::post('/users-permissions/{user}/activate', [UserPermissionController::class, 'activate'])->middleware('permission:users.edit')->name('users-permissions.activate');
        Route::get('/users-permissions/{user}/activity-logs', [UserPermissionController::class, 'activityLogs'])->middleware('permission:activity_logs.view')->name('users-permissions.activity-logs');
        
        // سجل الأنشطة
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->middleware('permission:activity_logs.view')->name('activity-logs.index');
        Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->middleware('permission:activity_logs.export')->name('activity-logs.export');
        Route::post('/activity-logs/clear', [ActivityLogController::class, 'clear'])->middleware('permission:activity_logs.clear')->name('activity-logs.clear');
        
        // النظام المالي
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\FinancialReportController::class, 'dashboard'])->middleware('permission:finance.dashboard')->name('dashboard');
            Route::get('/transactions', [\App\Http\Controllers\Admin\FinancialReportController::class, 'transactions'])->middleware('permission:finance.transactions')->name('transactions');
            Route::post('/transactions', [\App\Http\Controllers\Admin\FinancialReportController::class, 'storeTransaction'])->middleware('permission:finance.transactions.create')->name('transactions.store');
            Route::get('/monthly-audit', [\App\Http\Controllers\Admin\FinancialReportController::class, 'monthlyAudit'])->middleware('permission:finance.audit')->name('monthly-audit');
            Route::get('/reports', [\App\Http\Controllers\Admin\FinancialReportController::class, 'reports'])->middleware('permission:finance.reports')->name('reports');
            Route::post('/reports/monthly', [\App\Http\Controllers\Admin\FinancialReportController::class, 'generateMonthlyReport'])->middleware('permission:finance.reports.create')->name('reports.monthly');
            Route::post('/reports/custom', [\App\Http\Controllers\Admin\FinancialReportController::class, 'generateCustomReport'])->middleware('permission:finance.reports.create')->name('reports.custom');
            Route::get('/reports/{report}', [\App\Http\Controllers\Admin\FinancialReportController::class, 'showReport'])->middleware('permission:finance.reports')->name('report.show');
            Route::delete('/reports/{report}', [\App\Http\Controllers\Admin\FinancialReportController::class, 'destroyReport'])->middleware('permission:finance.reports.delete')->name('reports.destroy');
        });
        
        // المصروفات
        Route::prefix('expenses')->name('expenses.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ExpenseController::class, 'index'])->middleware('permission:expenses.view')->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\ExpenseController::class, 'create'])->middleware('permission:expenses.create')->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\ExpenseController::class, 'store'])->middleware('permission:expenses.create')->name('store');
            Route::get('/categories', [\App\Http\Controllers\Admin\ExpenseController::class, 'categories'])->middleware('permission:expenses.categories')->name('categories');
            Route::post('/categories', [\App\Http\Controllers\Admin\ExpenseController::class, 'storeCategory'])->middleware('permission:expenses.categories')->name('categories.store');
            Route::put('/categories/{category}', [\App\Http\Controllers\Admin\ExpenseController::class, 'updateCategory'])->middleware('permission:expenses.categories')->name('categories.update');
            Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\ExpenseController::class, 'destroyCategory'])->middleware('permission:expenses.categories')->name('categories.destroy');
            Route::get('/{expense}', [\App\Http\Controllers\Admin\ExpenseController::class, 'show'])->middleware('permission:expenses.view')->name('show');
            Route::get('/{expense}/edit', [\App\Http\Controllers\Admin\ExpenseController::class, 'edit'])->middleware('permission:expenses.edit')->name('edit');
            Route::put('/{expense}', [\App\Http\Controllers\Admin\ExpenseController::class, 'update'])->middleware('permission:expenses.edit')->name('update');
            Route::post('/{expense}/approve', [\App\Http\Controllers\Admin\ExpenseController::class, 'approve'])->middleware('permission:expenses.approve')->name('approve');
            Route::post('/{expense}/reject', [\App\Http\Controllers\Admin\ExpenseController::class, 'reject'])->middleware('permission:expenses.approve')->name('reject');
            Route::post('/{expense}/pay', [\App\Http\Controllers\Admin\ExpenseController::class, 'pay'])->middleware('permission:expenses.pay')->name('pay');
            Route::delete('/{expense}', [\App\Http\Controllers\Admin\ExpenseController::class, 'destroy'])->middleware('permission:expenses.delete')->name('destroy');
        });
        
        // خدمة العملاء (Support Tickets)
        Route::prefix('support')->name('support.')->middleware('permission:support.view')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SupportTicketController::class, 'index'])->name('index');
            Route::get('/unread-count', [\App\Http\Controllers\Admin\SupportTicketController::class, 'getUnreadCount'])->name('unread-count');
            Route::get('/{ticket}', [\App\Http\Controllers\Admin\SupportTicketController::class, 'show'])->name('show');
            Route::post('/{ticket}/reply', [\App\Http\Controllers\Admin\SupportTicketController::class, 'reply'])->middleware('permission:support.reply')->name('reply');
            Route::patch('/{ticket}/status', [\App\Http\Controllers\Admin\SupportTicketController::class, 'updateStatus'])->middleware('permission:support.status')->name('status');
            Route::patch('/{ticket}/priority', [\App\Http\Controllers\Admin\SupportTicketController::class, 'updatePriority'])->middleware('permission:support.priority')->name('priority');
            Route::get('/{ticket}/new-messages', [\App\Http\Controllers\Admin\SupportTicketController::class, 'getNewMessages'])->name('new-messages');
            Route::delete('/{ticket}', [\App\Http\Controllers\Admin\SupportTicketController::class, 'destroy'])->middleware('permission:support.delete')->name('destroy');
        });
        
        // Debug route for permissions (remove in production)
        Route::get('/debug/permissions', [\App\Http\Controllers\Admin\DebugPermissionController::class, 'debug'])->name('debug.permissions');
        Route::get('/debug/permissions/{userId}', [\App\Http\Controllers\Admin\DebugPermissionController::class, 'debug'])->name('debug.permissions.user');
    });
});
