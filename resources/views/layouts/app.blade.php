<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'منصة دوميرا - إيجار العقارات')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #1d313f;
            --primary-light: #2a4456;
            --primary-dark: #152431;
            --secondary: #6b8980;
            --secondary-light: #8aa69d;
            --secondary-dark: #536b63;
        }
        
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            direction: rtl;
            text-align: right;
        }
        
        /* Custom Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-slide-in {
            animation: slideIn 0.6s ease-out;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, var(--primary-dark), var(--secondary-dark));
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Card Hover Effect */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        /* Button Hover */
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(29, 49, 63, 0.4);
        }
        
        /* Input Focus */
        .input-focus:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
        }
        
        /* Navbar Glass Effect */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        /* Image Zoom Effect */
        .img-zoom {
            transition: transform 0.5s ease;
        }
        
        .img-zoom:hover {
            transform: scale(1.1);
        }
        
        /* Section Spacing */
        section {
            scroll-margin-top: 80px;
        }
        
        /* Loading Animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        .loading {
            animation: spin 1s linear infinite;
        }
        
        /* RTL Support */
        .space-x-reverse > * + * {
            margin-right: 0.5rem;
            margin-left: 0;
        }
        
        /* Mobile Sidebar */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            right: -100%;
            width: 300px;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: right 0.3s ease-in-out;
            overflow-y: auto;
        }
        
        .mobile-sidebar.active {
            right: 0;
        }
        
        .mobile-sidebar-overlay {
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
        }
        
        .mobile-sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .mobile-sidebar-header {
            background: linear-gradient(135deg, rgba(107, 137, 128, 0.3) 0%, rgba(29, 49, 63, 0.3) 100%);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .mobile-sidebar-close {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .mobile-sidebar-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .mobile-sidebar-content {
            padding: 1.5rem;
        }
        
        .mobile-sidebar-link {
            display: block;
            padding: 1rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 600;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            border-right: 3px solid transparent;
        }
        
        .mobile-sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-right-color: var(--secondary);
            transform: translateX(-5px);
        }
        
        .mobile-sidebar-link i {
            margin-left: 0.75rem;
            width: 20px;
        }
        
        /* Footer Styles */
        .footer {
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(107, 137, 128, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(107, 137, 128, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.3;
        }
        
        .footer-content {
            position: relative;
            z-index: 10;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .footer-logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(107, 137, 128, 0.3);
        }
        
        .footer-logo-text h3 {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(135deg, #FFFFFF 0%, #E0E7FF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .footer-logo-text p {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }
        
        .footer-section-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-section-title::after {
            content: '';
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, var(--secondary) 0%, transparent 100%);
            margin-right: 0.75rem;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-links a:hover {
            color: white;
            transform: translateX(-5px);
        }
        
        .footer-links a i {
            width: 16px;
            font-size: 0.875rem;
        }
        
        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .footer-contact-item:hover {
            color: white;
            transform: translateX(-5px);
        }
        
        .footer-contact-item i {
            width: 20px;
            font-size: 1rem;
            color: var(--secondary);
        }
        
        .footer-social {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .footer-social-link {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .footer-social-link:hover {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(107, 137, 128, 0.3);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            margin-top: 3rem;
        }
        
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr !important;
                gap: 2rem !important;
            }
            
            .footer-section-title::after {
                display: none;
            }
            
            .footer-social {
                justify-content: center;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <nav class="text-white shadow-xl sticky top-0 z-50 backdrop-blur-sm" style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-dark) 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 space-x-reverse group">
                        <div class="bg-gradient-to-br from-white to-gray-100 p-2.5 rounded-xl group-hover:scale-110 transition-all duration-300 shadow-lg" style="box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);">
                            <svg class="w-8 h-8" style="color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xl font-black" style="text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">دوميرا</div>
                            <div class="text-xs font-semibold" style="color: var(--secondary-light);">منصة إيجار العقارات</div>
                        </div>
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-6 space-x-reverse flex-1 justify-center">
                    <a href="{{ route('home') }}" class="text-white hover:opacity-80 font-bold transition-all duration-200 px-3 py-2 rounded-lg hover:bg-white/10 relative group">
                        الرئيسية
                        <span class="absolute bottom-0 right-0 left-0 h-0.5 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                    </a>
                    <a href="{{ route('properties') }}" class="text-white hover:opacity-80 font-bold transition-all duration-200 px-3 py-2 rounded-lg hover:bg-white/10 relative group">
                        العقارات
                        <span class="absolute bottom-0 right-0 left-0 h-0.5 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                    </a>
                    <a href="{{ route('booking.tracking') }}" class="text-white hover:opacity-80 font-bold transition-all duration-200 px-3 py-2 rounded-lg hover:bg-white/10 relative group">
                        تتبع الطلب
                        <span class="absolute bottom-0 right-0 left-0 h-0.5 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                    </a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-3 space-x-reverse">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl font-bold transition-all duration-200 flex items-center shadow-lg hover:shadow-xl hover:scale-105" style="background: white; color: var(--primary); box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);">
                            <i class="fas fa-tachometer-alt ml-2"></i>
                            لوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white/10 backdrop-blur-sm text-white px-5 py-2.5 rounded-xl font-bold hover:bg-white/20 transition-all duration-200 flex items-center border border-white/20">
                            <i class="fas fa-key ml-2"></i>
                            تسجيل دخول
                        </a>
                        <a href="{{ route('register') }}" class="text-white px-5 py-2.5 rounded-xl font-bold transition-all duration-200 flex items-center shadow-lg hover:shadow-xl hover:scale-105" style="background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);">
                            <i class="fas fa-user-plus ml-2"></i>
                            إنشاء حساب
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-white focus:outline-none p-2 rounded-lg hover:bg-white/10 transition-all duration-200" id="mobile-menu-button">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar-overlay" id="mobile-sidebar-overlay"></div>
    <div class="mobile-sidebar" id="mobile-sidebar">
        <div class="mobile-sidebar-header">
            <div class="flex items-center space-x-2 space-x-reverse">
                <div class="bg-white p-2 rounded-lg">
                    <svg class="w-6 h-6" style="color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <span class="text-lg font-bold">دوميرا</span>
            </div>
            <button class="mobile-sidebar-close" id="mobile-sidebar-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mobile-sidebar-content">
            <a href="{{ route('home') }}" class="mobile-sidebar-link">
                <i class="fas fa-home"></i> الرئيسية
            </a>
            <a href="{{ route('properties') }}" class="mobile-sidebar-link">
                <i class="fas fa-building"></i> العقارات
            </a>
            <a href="{{ route('booking.tracking') }}" class="mobile-sidebar-link">
                <i class="fas fa-search-location"></i> تتبع الطلب
            </a>
            @auth
                @if(auth()->user()->role === 'owner')
                <a href="{{ route('owner.properties.index') }}" class="mobile-sidebar-link">
                    <i class="fas fa-building"></i> وحداتي
                </a>
                @elseif(auth()->user()->role === 'tenant')
                <a href="{{ route('dashboard') }}" class="mobile-sidebar-link">
                    <i class="fas fa-calendar-check"></i> حجوزاتي
                </a>
                <a href="{{ route('tenant.inquiries.index') }}" class="mobile-sidebar-link">
                    <i class="fas fa-comments"></i> استفساراتي
                </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="block w-full font-bold py-3 px-4 rounded-lg text-center mb-2" style="background: white; color: var(--primary);">
                    <i class="fas fa-key ml-2"></i> تسجيل دخول
                </a>
                <a href="{{ route('register') }}" class="block w-full text-white font-bold py-3 px-4 rounded-lg text-center" style="background: var(--secondary);">
                    <i class="fas fa-user-plus ml-2"></i> إنشاء حساب
                </a>
            @endauth
        </div>
    </div>
    
    <script>
        // Mobile Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            const closeButton = document.getElementById('mobile-sidebar-close');
            
            function openSidebar() {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeSidebar() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            if (menuButton) {
                menuButton.addEventListener('click', openSidebar);
            }
            
            if (closeButton) {
                closeButton.addEventListener('click', closeSidebar);
            }
            
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }
            
            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                    closeSidebar();
                }
            });
        });
    </script>

    @if(session('success'))
        <div class="border px-4 py-3 rounded relative m-4" style="background: rgba(107, 137, 128, 0.1); border-color: var(--secondary); color: var(--secondary-dark);" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="footer-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-bottom: 3rem;">
                <!-- Logo & About -->
                <div>
                    <div class="footer-logo">
                        <div class="footer-logo-icon">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <div class="footer-logo-text">
                            <h3>دوميرا</h3>
                            <p>منصة إيجار العقارات</p>
                        </div>
                    </div>
                    <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.7; font-size: 0.9rem;">
                        منصة رائدة في مجال إيجار العقارات في مصر. نوفر لك أفضل الوحدات السكنية والتجارية مع ضمان الجودة والموثوقية.
                    </p>
                    <div class="footer-social">
                        @php
                            $facebook = \App\Models\Setting::get('social_facebook', '');
                            $twitter = \App\Models\Setting::get('social_twitter', '');
                            $instagram = \App\Models\Setting::get('social_instagram', '');
                            $linkedin = \App\Models\Setting::get('social_linkedin', '');
                            $youtube = \App\Models\Setting::get('social_youtube', '');
                        @endphp
                        
                        @if($facebook)
                        <a href="{{ $facebook }}" target="_blank" rel="noopener noreferrer" class="footer-social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif
                        
                        @if($twitter)
                        <a href="{{ $twitter }}" target="_blank" rel="noopener noreferrer" class="footer-social-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                        
                        @if($instagram)
                        <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer" class="footer-social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        
                        @if($linkedin)
                        <a href="{{ $linkedin }}" target="_blank" rel="noopener noreferrer" class="footer-social-link">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        @endif
                        
                        @if($youtube)
                        <a href="{{ $youtube }}" target="_blank" rel="noopener noreferrer" class="footer-social-link">
                            <i class="fab fa-youtube"></i>
                        </a>
                        @endif
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="footer-section-title">
                        <i class="fas fa-link"></i>
                        روابط سريعة
                    </h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> الرئيسية</a></li>
                        <li><a href="{{ route('properties') }}"><i class="fas fa-building"></i> العقارات</a></li>
                        <li><a href="{{ route('booking.tracking') }}"><i class="fas fa-search-location"></i> تتبع حالة الطلب</a></li>
                        @auth
                        <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a></li>
                        @else
                        <li><a href="{{ route('login') }}"><i class="fas fa-key"></i> تسجيل الدخول</a></li>
                        <li><a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> إنشاء حساب</a></li>
                        @endauth
                    </ul>
                </div>
                
                <!-- Services -->
                <div>
                    <h3 class="footer-section-title">
                        <i class="fas fa-briefcase"></i>
                        خدماتنا
                    </h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('properties') }}?type=residential"><i class="fas fa-home"></i> وحدات سكنية</a></li>
                        <li><a href="{{ route('properties') }}?type=commercial"><i class="fas fa-store"></i> وحدات تجارية</a></li>
                        <li><a href="{{ route('booking.tracking') }}"><i class="fas fa-calendar-check"></i> تتبع حالة الطلب</a></li>
                        <li><a href="#"><i class="fas fa-headset"></i> خدمة العملاء</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="footer-section-title">
                        <i class="fas fa-envelope"></i>
                        تواصل معنا
                    </h3>
                    @php
                        $footerPhone = \App\Models\Setting::get('footer_phone', '01000000000');
                        $footerEmail = \App\Models\Setting::get('footer_email', 'info@domiraa.com');
                        $footerAddress = \App\Models\Setting::get('footer_address', 'القاهرة، مصر');
                    @endphp
                    
                    @if($footerPhone)
                    <div class="footer-contact-item">
                        <i class="fas fa-phone"></i>
                        <a href="tel:{{ $footerPhone }}" style="color: inherit; text-decoration: none;">{{ $footerPhone }}</a>
                    </div>
                    @endif
                    
                    @if($footerEmail)
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:{{ $footerEmail }}" style="color: inherit; text-decoration: none;">{{ $footerEmail }}</a>
                    </div>
                    @endif
                    
                    @if($footerAddress)
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $footerAddress }}</span>
                    </div>
                    @endif
                    <div class="footer-contact-item">
                        <i class="fas fa-clock"></i>
                        <span>24/7 متاح</span>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center; text-align: center;">
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.875rem; margin: 0;">
                        &copy; {{ date('Y') }} منصة دوميرا. جميع الحقوق محفوظة.
                    </p>
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center;">
                        <a href="#" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.875rem; transition: color 0.3s ease;">شروط الاستخدام</a>
                        <a href="#" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.875rem; transition: color 0.3s ease;">سياسة الخصوصية</a>
                        <a href="#" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.875rem; transition: color 0.3s ease;">الأسئلة الشائعة</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Support Chat Widget -->
    @include('components.support-chat-widget')
    
    <!-- AJAX Setup for CSRF Token -->
    <script>
        // Setup CSRF token for all AJAX requests
        (function() {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                // For fetch API
                const originalFetch = window.fetch;
                window.fetch = function(...args) {
                    const options = args[1] || {};
                    if (!options.headers) {
                        options.headers = {};
                    }
                    if (typeof options.headers === 'object' && !(options.headers instanceof Headers)) {
                        options.headers['X-CSRF-TOKEN'] = token.content;
                        options.headers['X-Requested-With'] = 'XMLHttpRequest';
                    }
                    args[1] = options;
                    return originalFetch.apply(this, args);
                };
                
                // For XMLHttpRequest
                const originalOpen = XMLHttpRequest.prototype.open;
                XMLHttpRequest.prototype.open = function(method, url, ...rest) {
                    this.addEventListener('loadstart', function() {
                        if (this.readyState === 1) {
                            this.setRequestHeader('X-CSRF-TOKEN', token.content);
                            this.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        }
                    });
                    return originalOpen.apply(this, [method, url, ...rest]);
                };
            }
        })();
        
        // Refresh CSRF token on page visibility change (to prevent expiration)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                fetch('/csrf-token', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                        if (tokenMeta) {
                            tokenMeta.content = data.token;
                        }
                        // Update all forms
                        document.querySelectorAll('input[name="_token"]').forEach(input => {
                            input.value = data.token;
                        });
                    }
                })
                .catch(() => {
                    // Ignore errors
                });
            }
        });
    </script>
    
    @include('components.csrf-handler')
    
    @stack('scripts')
</body>
</html>


