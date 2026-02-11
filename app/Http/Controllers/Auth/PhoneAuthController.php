<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PhoneAuthController extends Controller
{
    /**
     * Maximum login attempts per minute
     */
    protected $maxAttempts = 5;
    
    /**
     * Decay minutes for rate limiting
     */
    protected $decayMinutes = 1;

    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    
    public function login(Request $request)
    {
        // Rate limiting - prevent brute force attacks
        $key = $this->throttleKey($request);
        
        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            // Log suspicious activity
            $this->logSuspiciousActivity($request, 'too_many_login_attempts', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'attempts' => RateLimiter::attempts($key),
            ]);
            
            throw ValidationException::withMessages([
                'login' => "تم تجاوز عدد المحاولات المسموح. يرجى المحاولة مرة أخرى بعد " . ceil($seconds / 60) . " دقيقة.",
            ]);
        }

        // Validate input
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9@._+\-() ]+$/'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'login.regex' => 'يحتوي حقل تسجيل الدخول على أحرف غير مسموحة.',
            'password.max' => 'كلمة المرور طويلة جداً.',
        ]);

        // Sanitize input
        $login = trim(strip_tags($validated['login']));
        
        // Check if account is suspended
        $loginField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = User::where($loginField, $login)->first();
        
        if ($user && $user->isSuspended()) {
            RateLimiter::hit($key, $this->decayMinutes * 60);
            
            $this->logSuspiciousActivity($request, 'login_attempt_suspended_account', [
                'user_id' => $user->id,
                'login_field' => $loginField,
            ]);
            
            return back()->withErrors([
                'login' => 'تم إيقاف هذا الحساب. يرجى التواصل مع الدعم.',
            ])->onlyInput('login');
        }
        
        $credentials = [
            $loginField => $login,
            'password' => $validated['password'],
        ];

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Clear rate limiter on successful login
            RateLimiter::clear($key);
            
            // Regenerate session ID for security
            $request->session()->regenerate();
            
            // Log successful login
            ActivityLog::create([
                'log_name' => 'auth',
                'description' => 'تسجيل دخول ناجح',
                'subject_type' => User::class,
                'subject_id' => Auth::id(),
                'causer_type' => User::class,
                'causer_id' => Auth::id(),
                'properties' => [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return redirect()->intended(route('dashboard'))
                ->with('success', 'مرحباً بعودتك!');
        }

        // Increment rate limiter on failed attempt
        RateLimiter::hit($key, $this->decayMinutes * 60);
        
        // Log failed login attempt (without revealing if account exists)
        $this->logSuspiciousActivity($request, 'failed_login_attempt', [
            'login_field' => $loginField,
            'attempts' => RateLimiter::attempts($key),
        ]);

        // Generic error message to prevent account enumeration
        return back()->withErrors([
            'login' => 'بيانات الدخول غير صحيحة.',
        ])->onlyInput('login');
    }
    
    public function register(Request $request)
    {
        // Rate limiting for registration
        $key = 'register:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            
            $this->logSuspiciousActivity($request, 'too_many_registration_attempts', [
                'ip' => $request->ip(),
                'attempts' => RateLimiter::attempts($key),
            ]);
            
            throw ValidationException::withMessages([
                'email' => "تم تجاوز عدد محاولات التسجيل المسموح. يرجى المحاولة مرة أخرى بعد " . ceil($seconds / 60) . " دقيقة.",
            ]);
        }

        // Simplified validation - only essential checks
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'role' => 'required|in:tenant,owner',
            'password' => 'required|confirmed|min:6',
        ]);

        // Sanitize inputs
        $name = trim(strip_tags($validated['name']));
        $email = strtolower(trim($validated['email']));
        $phone = preg_replace('/[^0-9]/', '', $validated['phone']);

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'account_status' => 'active',
        ]);

        // Clear rate limiter
        RateLimiter::clear($key);

        // Log successful registration
        ActivityLog::create([
            'log_name' => 'auth',
            'description' => 'إنشاء حساب جديد',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'properties' => [
                'role' => $user->role,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Auth::login($user);
        
        // Regenerate session ID
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'تم إنشاء الحساب بنجاح!');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
        ]);

        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => 'User ' . substr($request->phone, -4),
                'email' => $request->phone . '@domiraa.com',
                'password' => Hash::make(Str::random(16)),
                'role' => 'tenant',
            ]
        );

        $user->verification_code = $code;
        $user->verification_code_expires_at = now()->addMinutes(10);
        $user->save();

        // هنا يمكن إرسال الكود عبر SMS
        // TODO: إضافة خدمة SMS

        return response()->json([
            'message' => 'تم إرسال رمز التحقق',
            'code' => $code, // في الإنتاج يجب إزالة هذا السطر
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:4',
        ]);

        $user = User::where('phone', $request->phone)
            ->where('verification_code', $request->code)
            ->where('verification_code_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['code' => 'رمز التحقق غير صحيح أو منتهي الصلاحية']);
        }

        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        // Log logout
        if (Auth::check()) {
            ActivityLog::create([
                'log_name' => 'auth',
                'description' => 'تسجيل خروج',
                'subject_type' => User::class,
                'subject_id' => Auth::id(),
                'causer_type' => User::class,
                'causer_id' => Auth::id(),
                'properties' => [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }
    
    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        return 'login:' . $request->ip() . ':' . $request->input('login');
    }
    
    /**
     * Log suspicious activity
     */
    protected function logSuspiciousActivity(Request $request, string $type, array $data = []): void
    {
        ActivityLog::create([
            'log_name' => 'security',
            'description' => 'نشاط مشبوه: ' . $type,
            'subject_type' => null,
            'subject_id' => null,
            'causer_type' => null,
            'causer_id' => null,
            'properties' => array_merge([
                'type' => $type,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
            ], $data),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
