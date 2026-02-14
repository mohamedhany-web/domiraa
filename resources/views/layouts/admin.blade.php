<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة تحكم الأدمن - منصة دوميرا')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            font-family: 'Cairo', 'Poppins', sans-serif;
        }
        
        :root {
            --primary: #1d313f;
            --primary-light: #2a4456;
            --primary-dark: #152431;
            --secondary: #6b8980;
            --secondary-light: #8aa69d;
            --secondary-dark: #536b63;
        }
        
        body {
            direction: rtl;
            text-align: right;
            background: linear-gradient(135deg, #F8FAFC 0%, rgba(107, 137, 128, 0.1) 100%);
            min-height: 100vh;
        }
        
        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Sidebar - Modern Design */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            position: fixed;
            right: 0;
            top: 0;
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 1024px) {
            .sidebar {
                width: 280px;
                transform: translateX(100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(135deg, rgba(107, 137, 128, 0.2) 0%, rgba(29, 49, 63, 0.2) 100%);
            position: relative;
        }
        
        .sidebar-close {
            display: none;
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-50%) rotate(90deg);
        }
        
        @media (max-width: 1024px) {
            .sidebar-close {
                display: flex;
            }
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
        }
        
        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(107, 137, 128, 0.4);
            flex-shrink: 0;
        }
        
        .logo-text h2 {
            font-size: 1.25rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(135deg, #FFFFFF 0%, #E0E7FF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .logo-text p {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
            font-weight: 500;
        }
        
        @media (max-width: 480px) {
            .logo-icon {
                width: 40px;
                height: 40px;
            }
            
            .logo-text h2 {
                font-size: 1.125rem;
            }
            
            .logo-text p {
                font-size: 0.65rem;
            }
        }
        
        .sidebar-menu {
            padding: 1.5rem 0;
        }
        
        .menu-section {
            margin-bottom: 1.5rem;
        }
        
        .menu-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 3px solid transparent;
            position: relative;
            font-weight: 600;
            font-size: 0.875rem;
            margin: 0.25rem 0;
        }
        
        .menu-item::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: linear-gradient(90deg, rgba(107, 137, 128, 0.3) 0%, rgba(29, 49, 63, 0.3) 100%);
            transition: width 0.3s ease;
        }
        
        .menu-item:hover::before,
        .menu-item.active::before {
            width: 100%;
        }
        
        .menu-item:hover {
            color: white;
            transform: translateX(-5px);
        }
        
        .menu-item.active {
            background: linear-gradient(90deg, rgba(107, 137, 128, 0.2) 0%, rgba(29, 49, 63, 0.2) 100%);
            color: white;
            border-right-color: var(--secondary);
        }
        
        .menu-item i {
            width: 22px;
            margin-left: 0.875rem;
            font-size: 1rem;
            position: relative;
            z-index: 1;
            text-align: center;
        }
        
        .menu-item span {
            position: relative;
            z-index: 1;
            flex: 1;
        }
        
        @media (max-width: 480px) {
            .menu-item {
                padding: 0.75rem 1rem;
                font-size: 0.85rem;
            }
            
            .menu-item i {
                width: 20px;
                font-size: 0.95rem;
            }
            
            .menu-section-title {
                padding: 0.5rem 1rem;
                font-size: 0.65rem;
            }
        }
        
        /* Main Content */
        .main-content {
            margin-right: 260px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        @media (max-width: 1024px) {
            .main-content {
                margin-right: 0;
            }
        }
        
        /* Header - Modern Design */
        .admin-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 0.875rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        
        .header-title {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-menu {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #F8FAFC 0%, rgba(107, 137, 128, 0.15) 100%);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(29, 49, 63, 0.1);
        }
        
        .user-menu:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(29, 49, 63, 0.15);
        }
        
        .user-menu.active {
            box-shadow: 0 8px 20px rgba(29, 49, 63, 0.15);
        }
        
        .dropdown-icon {
            color: #6B7280;
            transition: transform 0.3s ease;
            font-size: 0.75rem;
        }
        
        .user-menu.active .dropdown-icon {
            transform: rotate(180deg);
        }
        
        /* Dropdown Menu */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            left: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            border: 1px solid rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .user-menu.active .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .dropdown-item:last-child {
            border-bottom: none;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #F8FAFC 0%, rgba(107, 137, 128, 0.1) 100%);
            color: var(--primary);
        }
        
        .dropdown-item.logout:hover {
            background: linear-gradient(135deg, #FEF2F2 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #DC2626;
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
            font-size: 0.95rem;
        }
        
        .dropdown-item form {
            margin: 0;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0;
        }
        
        .dropdown-item button {
            background: none;
            border: none;
            color: inherit;
            font-size: inherit;
            font-weight: inherit;
            cursor: pointer;
            padding: 0;
            width: 100%;
            text-align: right;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
            flex-shrink: 0;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-info h3 {
            font-size: 0.875rem;
            font-weight: 700;
            color: #1F2937;
            margin: 0;
            line-height: 1.2;
        }
        
        .user-info p {
            font-size: 0.7rem;
            color: #6B7280;
            margin: 0;
            line-height: 1.2;
        }
        
        @media (max-width: 768px) {
            .admin-header {
                padding: 0.75rem 1rem;
            }
            
            .header-title {
                font-size: 1.125rem;
            }
            
            .user-menu {
                padding: 0.5rem;
                gap: 0.5rem;
            }
            
            .user-info {
                display: none;
            }
            
            .user-avatar {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 480px) {
            .admin-header {
                padding: 0.625rem 0.75rem;
            }
            
            .header-title {
                font-size: 1rem;
            }
            
            .header-content {
                gap: 0.5rem;
            }
        }
        
        /* Content Area */
        .content-area {
            padding: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .content-area {
                padding: 1rem 0.75rem;
            }
        }
        
        @media (max-width: 480px) {
            .content-area {
                padding: 0.75rem 0.5rem;
            }
        }
        
        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
        }
        
        .mobile-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 49, 63, 0.4);
        }
        
        @media (max-width: 1024px) {
            .main-content {
                margin-right: 0;
            }
            
            .mobile-toggle {
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 300px;
            }
        }
        
        @media (max-width: 480px) {
            .sidebar {
                width: 85%;
                max-width: 320px;
            }
            
            .sidebar-header {
                padding: 1.25rem 1rem;
            }
            
            .sidebar-close {
                left: 0.75rem;
                width: 32px;
                height: 32px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button class="sidebar-close" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
            <div class="sidebar-logo">
                <div class="logo-icon">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <div class="logo-text">
                    <h2>دوميرا</h2>
                    <p>لوحة تحكم الأدمن</p>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-menu">
            @php
                // Always get fresh user instance to ensure we have latest permissions
                $currentUser = auth()->user();
                // Clear any cached relationships to force fresh load
                if ($currentUser) {
                    $currentUser->unsetRelation('roleModel');
                    $currentUser->unsetRelation('directPermissions');
                    // Force reload roleModel and permissions
                    $currentUser->load(['roleModel.permissions', 'directPermissions']);
                }
            @endphp
            <div class="menu-section">
                <div class="menu-section-title">القائمة الرئيسية</div>
                @if($currentUser && $currentUser->hasPermission('dashboard.view'))
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>لوحة التحكم</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('properties.view'))
                <a href="{{ route('admin.properties') }}" class="menu-item {{ request()->routeIs('admin.properties*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>الوحدات</span>
                </a>
                @endif
            </div>
            
            <div class="menu-section">
                <div class="menu-section-title">الإدارة</div>
                @if($currentUser && $currentUser->hasPermission('users.view'))
                <a href="{{ route('admin.users') }}" class="menu-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>المستخدمون</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('bookings.view'))
                <a href="{{ route('admin.bookings') }}" class="menu-item {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>الحجوزات</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('inquiries.view'))
                <a href="{{ route('admin.inquiries') }}" class="menu-item {{ request()->routeIs('admin.inquiries*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>الاستفسارات</span>
                </a>
                @endif
            </div>
            
            <div class="menu-section">
                <div class="menu-section-title">المالية</div>
                @if($currentUser && $currentUser->hasPermission('payments.view'))
                <a href="{{ route('admin.payments') }}" class="menu-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>المدفوعات والفواتير</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('wallets.view'))
                <a href="{{ route('admin.wallets') }}" class="menu-item {{ request()->routeIs('admin.wallets*') ? 'active' : '' }}">
                    <i class="fas fa-wallet"></i>
                    <span>المحافظ</span>
                </a>
                @endif
            </div>
            
            <div class="menu-section">
                <div class="menu-section-title">النظام المالي</div>
                @if($currentUser && $currentUser->hasPermission('finance.dashboard'))
                <a href="{{ route('admin.finance.dashboard') }}" class="menu-item {{ request()->routeIs('admin.finance.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>لوحة المالية</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('expenses.view'))
                <a href="{{ route('admin.expenses.index') }}" class="menu-item {{ request()->routeIs('admin.expenses*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>المصروفات</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('finance.transactions'))
                <a href="{{ route('admin.finance.transactions') }}" class="menu-item {{ request()->routeIs('admin.finance.transactions') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt"></i>
                    <span>المعاملات</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('finance.audit'))
                <a href="{{ route('admin.finance.monthly-audit') }}" class="menu-item {{ request()->routeIs('admin.finance.monthly-audit') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>الجرد الشهري</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('finance.reports'))
                <a href="{{ route('admin.finance.reports') }}" class="menu-item {{ request()->routeIs('admin.finance.reports*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>التقارير</span>
                </a>
                @endif
            </div>
            
            <div class="menu-section">
                <div class="menu-section-title">الدعم والتواصل</div>
                @if($currentUser && $currentUser->hasPermission('support.view'))
                <a href="{{ route('admin.support.index') }}" class="menu-item {{ request()->routeIs('admin.support*') ? 'active' : '' }}">
                    <i class="fas fa-headset"></i>
                    <span>خدمة العملاء</span>
                    @php
                        $unreadSupport = \App\Models\SupportMessage::where('is_admin', false)->where('is_read', false)->count();
                    @endphp
                    @if($unreadSupport > 0)
                    <span style="background: #DC2626; color: white; padding: 0.125rem 0.5rem; border-radius: 10px; font-size: 0.75rem; margin-right: auto;">{{ $unreadSupport }}</span>
                    @endif
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('inquiries.view'))
                <a href="{{ route('admin.inquiries') }}" class="menu-item {{ request()->routeIs('admin.inquiries*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>الاستفسارات</span>
                </a>
                @endif
            </div>
            
            <div class="menu-section">
                <div class="menu-section-title">الأمان والصلاحيات</div>
                @if($currentUser && ($currentUser->hasPermission('users.view') || $currentUser->hasPermission('permissions.view')))
                <a href="{{ route('admin.users-permissions.index') }}" class="menu-item {{ request()->routeIs('admin.users-permissions*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i>
                    <span>المستخدمون والصلاحيات</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('roles.view'))
                <a href="{{ route('admin.roles.index') }}" class="menu-item {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i>
                    <span>الأدوار</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('permissions.view'))
                <a href="{{ route('admin.permissions.index') }}" class="menu-item {{ request()->routeIs('admin.permissions*') ? 'active' : '' }}">
                    <i class="fas fa-key"></i>
                    <span>الصلاحيات</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('activity_logs.view'))
                <a href="{{ route('admin.activity-logs.index') }}" class="menu-item {{ request()->routeIs('admin.activity-logs*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>سجل الأنشطة</span>
                </a>
                @endif
                @if($currentUser && $currentUser->hasPermission('complaints.view'))
                <a href="{{ route('admin.complaints') }}" class="menu-item {{ request()->routeIs('admin.complaints*') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>الشكاوى والبلاغات</span>
                </a>
                @endif
            </div>
            
            <div class="menu-section">
                <div class="menu-section-title">المحتوى</div>
                @if($currentUser && $currentUser->hasPermission('content.view'))
                <a href="{{ route('admin.content') }}" class="menu-item {{ request()->routeIs('admin.content*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>إدارة المحتوى</span>
                </a>
                @endif
            </div>
            
            <div class="menu-section">
                <div class="menu-section-title">عام</div>
                @if($currentUser && $currentUser->hasPermission('settings.view'))
                <a href="{{ route('admin.settings.pricing') }}" class="menu-item {{ request()->routeIs('admin.settings.pricing*') ? 'active' : '' }}">
                    <i class="fas fa-coins"></i>
                    <span>إعدادات الأسعار</span>
                </a>
                <a href="{{ route('admin.property-types.index') }}" class="menu-item {{ request()->routeIs('admin.property-types.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>أنواع الوحدة</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>الإعدادات العامة</span>
                </a>
                @endif
                <a href="{{ route('home') }}" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span>الرئيسية</span>
                </a>
            </div>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-content">
                <div class="flex items-center gap-4">
                    <button class="mobile-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="header-title">@yield('page-title', 'لوحة التحكم')</h1>
                </div>
                
                <div class="header-actions">
                    <div class="user-menu" id="userMenuDropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <h3>{{ auth()->user()->name }}</h3>
                            <p>مدير النظام</p>
                        </div>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                        
                        <div class="user-dropdown">
                            <a href="{{ route('admin.users-permissions.show', auth()->user()->id) }}" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <span>الملف الشخصي</span>
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>الإعدادات</span>
                            </a>
                            <a href="#" class="dropdown-item logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>تسجيل الخروج</span>
                            </a>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Content Area -->
        <main class="content-area">
            @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-r-4 border-green-500 text-green-800 px-6 py-4 rounded-lg mb-6 shadow-lg" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-2xl ml-3"></i>
                        <div>
                            <strong class="font-bold">نجاح!</strong>
                            <span class="block sm:inline mr-2">{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            @endif
            
            @if($errors->any())
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-r-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6 shadow-lg" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-2xl ml-3"></i>
                        <div>
                            <strong class="font-bold">خطأ!</strong>
                            <ul class="mt-2">
                                @foreach($errors->all() as $error)
                                    <li><i class="fas fa-dot-circle text-xs ml-2"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <script>
        // Sidebar Functions
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        function openSidebar() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.add('open');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeSidebar() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Toggle Sidebar
        sidebarToggle?.addEventListener('click', function() {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
        
        // Close Sidebar
        sidebarClose?.addEventListener('click', closeSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);
        
        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });
        
        // Handle Window Resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                closeSidebar();
            }
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 1024 && 
                sidebar && 
                !sidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target) &&
                sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });
        
        // User Menu Dropdown
        const userMenuDropdown = document.getElementById('userMenuDropdown');
        if (userMenuDropdown) {
            userMenuDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (userMenuDropdown && !userMenuDropdown.contains(e.target)) {
                    userMenuDropdown.classList.remove('active');
                }
            });
        }
    </script>
    @include('components.csrf-handler')
    
    @stack('scripts')
</body>
</html>


