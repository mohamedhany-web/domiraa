<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة تحكم المؤجر - منصة دوميرا')</title>
    
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
            z-index: 99;
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
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
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
        
        /* Hide scrollbar but keep functionality */
        .sidebar {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }
        
        .sidebar::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
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
        
        .menu-badge {
            background: rgba(239, 68, 68, 0.2);
            color: #FCA5A5;
            padding: 0.125rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-right: 0.5rem;
        }
        
        /* Main Content */
        .main-content {
            margin-right: 260px;
            min-height: 100vh;
            transition: all 0.3s ease;
            width: calc(100% - 260px);
            overflow-x: hidden;
            position: relative;
            z-index: 1;
        }

        @media (max-width: 1024px) {
            .main-content {
                margin-right: 0;
                width: 100%;
            }
            
            .sidebar {
                z-index: 1000;
            }
            
            .sidebar-overlay {
                z-index: 999;
            }
        }
        
        /* Header */
        .owner-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 0.875rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 101;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            width: 100%;
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
            z-index: 102;
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
            font-size: 0.9rem;
            font-weight: 700;
            color: #1F2937;
            margin: 0;
        }
        
        .user-info p {
            font-size: 0.75rem;
            color: #6B7280;
            margin: 0;
        }
        
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
        
        /* Content Area */
        .content-area {
            padding: 1.5rem;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            position: relative;
            z-index: 1;
        }
        
        @media (max-width: 1024px) {
            .content-area {
                padding: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .content-area {
                padding: 0.75rem;
            }
            
            .content-area table,
            .content-area .table {
                font-size: 0.75rem;
            }
            
            .content-area .stat-card {
                min-height: auto;
                padding: 1rem;
            }
            
            .content-area .stat-value {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .content-area {
                padding: 0.5rem;
            }
            
            .content-area .stat-card {
                padding: 0.875rem;
            }
            
            .content-area .stat-value {
                font-size: 1.375rem;
            }
        }
        
        /* Header Responsive */
        @media (max-width: 768px) {
            .owner-header {
                padding: 0.75rem 1rem;
            }
            
            .header-title {
                font-size: 1.25rem;
            }
            
            .header-content {
                flex-wrap: wrap;
                gap: 0.75rem;
            }
            
            .user-menu {
                padding: 0.5rem 0.75rem;
            }
            
            .user-info h3 {
                font-size: 0.8rem;
            }
            
            .user-info p {
                font-size: 0.7rem;
            }
        }
        
        @media (max-width: 480px) {
            .owner-header {
                padding: 0.625rem 0.75rem;
            }
            
            .header-title {
                font-size: 1.125rem;
            }
            
            .user-menu {
                padding: 0.5rem;
            }
            
            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.875rem;
            }
            
            .user-info {
                display: none;
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
                    <i class="fas fa-building"></i>
                </div>
                <div class="logo-text">
                    <h2>دوميرا</h2>
                    <p>لوحة تحكم المؤجر</p>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">القائمة الرئيسية</div>
                <a href="{{ route('owner.dashboard') }}" class="menu-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>لوحة التحكم</span>
                </a>
                <a href="{{ route('owner.properties.index') }}" class="menu-item {{ request()->routeIs('owner.properties*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>الوحدات</span>
                </a>
            </div>
            
            <div class="menu-section">
                <div class="menu-section-title">الإدارة</div>
                <a href="{{ route('owner.inspections') }}" class="menu-item {{ request()->routeIs('owner.inspections*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>طلبات المعاينة</span>
                    @if(\App\Models\Booking::whereHas('property', function($q) { $q->where('user_id', auth()->id()); })->where('status', 'pending')->count() > 0)
                    <span class="menu-badge">{{ \App\Models\Booking::whereHas('property', function($q) { $q->where('user_id', auth()->id()); })->where('status', 'pending')->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('owner.bookings') }}" class="menu-item {{ request()->routeIs('owner.bookings*') ? 'active' : '' }}">
                    <i class="fas fa-file-contract"></i>
                    <span>الحجوزات والعقود</span>
                </a>
            </div>
            
            <div class="menu-section">
                <div class="menu-section-title">التواصل</div>
                <a href="{{ route('owner.messages') }}" class="menu-item {{ request()->routeIs('owner.messages*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>الرسائل</span>
                    @if(\App\Models\Message::where('receiver_id', auth()->id())->where('read_at', null)->count() > 0)
                    <span class="menu-badge">{{ \App\Models\Message::where('receiver_id', auth()->id())->where('read_at', null)->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('owner.ratings') }}" class="menu-item {{ request()->routeIs('owner.ratings*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>التقييمات</span>
                </a>
            </div>
            
            <div class="menu-section">
                <div class="menu-section-title">عام</div>
                <a href="{{ route('owner.account') }}" class="menu-item {{ request()->routeIs('owner.account*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>حسابي</span>
                </a>
                <a href="{{ route('owner.support') }}" class="menu-item {{ request()->routeIs('owner.support*') ? 'active' : '' }}">
                    <i class="fas fa-headset"></i>
                    <span>الدعم الفني</span>
                </a>
                <a href="{{ route('owner.settings') }}" class="menu-item {{ request()->routeIs('owner.settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>الإعدادات</span>
                </a>
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
        <header class="owner-header">
            <div class="header-content">
                <div style="display: flex; align-items: center; gap: 1rem;">
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
                            <p>مؤجر</p>
                        </div>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                        
                        <div class="user-dropdown">
                            <a href="{{ route('owner.account') }}" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <span>الملف الشخصي</span>
                            </a>
                            <a href="{{ route('owner.settings') }}" class="dropdown-item">
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
        <div class="content-area">
            @if(session('success'))
            <div style="background: #D1FAE5; border: 1px solid #6b8980; color: #536b63; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif
            
            @if(session('error'))
            <div style="background: #FEE2E2; border: 1px solid #EF4444; color: #DC2626; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif
            
            @if($errors->any())
            <div style="background: #FEE2E2; border: 1px solid #EF4444; color: #DC2626; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($errors->all() as $error)
                    <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $error }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <script>
        // Mobile Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggle = document.getElementById('sidebarToggle');
            const close = document.getElementById('sidebarClose');
            
            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            if (toggle) {
                toggle.addEventListener('click', openSidebar);
            }
            
            if (close) {
                close.addEventListener('click', closeSidebar);
            }
            
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }
            
            // Close on escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                    closeSidebar();
                }
            });
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



