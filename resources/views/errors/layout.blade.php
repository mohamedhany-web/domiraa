<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'خطأ - منصة دوميرا')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
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
        * { font-family: 'Cairo', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
        body {
            direction: rtl;
            text-align: right;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #F9FAFB 0%, #F0F9FF 50%, #E8F4F8 100%);
        }
        .error-nav {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-dark) 100%);
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 20px rgba(29, 49, 63, 0.2);
        }
        .error-nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .error-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
            font-weight: 800;
            font-size: 1.25rem;
            transition: opacity 0.2s;
        }
        .error-logo:hover { opacity: 0.9; color: white; }
        .error-logo-icon {
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-logo-icon svg { width: 24px; height: 24px; color: white; }
        .error-logo-sub { font-size: 0.7rem; font-weight: 600; color: var(--secondary-light); display: block; margin-top: 2px; }
        .error-nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .error-nav-links a:hover { background: rgba(255,255,255,0.15); color: white; }
        .error-nav-links a i { margin-left: 0.25rem; }
        .error-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }
        .error-card {
            max-width: 520px;
            width: 100%;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(29, 49, 63, 0.12), 0 4px 15px rgba(0,0,0,0.06);
            padding: 3rem 2.5rem;
            text-align: center;
            border: 1px solid rgba(107, 137, 128, 0.12);
            animation: errFade 0.6s ease-out;
        }
        @keyframes errFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .error-code {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        .error-icon-wrap {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, rgba(29,49,63,0.08) 0%, rgba(107,137,128,0.12) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-icon-wrap i { font-size: 2.5rem; color: var(--secondary); }
        .error-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.75rem;
        }
        .error-message {
            color: #6B7280;
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }
        .error-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
        }
        .error-btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(29, 49, 63, 0.25);
        }
        .error-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(29, 49, 63, 0.35);
            color: white;
        }
        .error-btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        .error-btn-secondary:hover {
            background: var(--primary);
            color: white;
        }
        .error-footer {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: rgba(255,255,255,0.85);
            font-size: 0.9rem;
            text-align: center;
        }
        .error-footer a { color: var(--secondary-light); text-decoration: none; font-weight: 600; }
        .error-footer a:hover { text-decoration: underline; color: white; }
    </style>
</head>
<body>
    <header class="error-nav">
        <div class="error-nav-inner">
            <a href="{{ url('/') }}" class="error-logo">
                <div class="error-logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <div>
                    <span>دوميرا</span>
                    <span class="error-logo-sub">منصة إيجار العقارات</span>
                </div>
            </a>
            <nav class="error-nav-links">
                <a href="{{ url('/') }}"><i class="fas fa-home"></i> الرئيسية</a>
                <a href="{{ url('/search') }}"><i class="fas fa-search"></i> البحث</a>
            </nav>
        </div>
    </header>

    <main class="error-main">
        <div class="error-card">
            @yield('content')
        </div>
    </main>

    <footer class="error-footer">
        <a href="{{ url('/') }}">العودة للرئيسية</a>
        <span style="margin: 0 0.5rem;">|</span>
        <span>منصة دوميرا &copy; {{ date('Y') }}</span>
    </footer>
</body>
</html>
