<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Maximum login attempts before lockout
     */
    protected int $maxAttempts = 5;

    /**
     * Lockout duration in seconds (5 minutes)
     */
    protected int $decaySeconds = 300;

    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }

    /**
     * Get the rate limiter key for login attempts
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check if too many login attempts
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            
            // Log failed attempt due to rate limiting
            ActivityLogService::log('login_blocked', "Login diblokir karena terlalu banyak percobaan untuk email: {$request->email}");
            
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam " . ceil($seconds / 60) . " menit.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Clear rate limiter on successful login
            RateLimiter::clear($this->throttleKey($request));
            
            $request->session()->regenerate();
            
            // Log aktivitas login
            ActivityLogService::logLogin('Admin login berhasil');
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali!');
        }

        // Increment login attempts on failure
        RateLimiter::hit($this->throttleKey($request), $this->decaySeconds);
        
        $attemptsLeft = $this->maxAttempts - RateLimiter::attempts($this->throttleKey($request));
        
        // Log failed login attempt
        ActivityLogService::log('login_failed', "Login gagal untuk email: {$request->email}. Sisa percobaan: {$attemptsLeft}");

        return back()->withErrors([
            'email' => "Email atau password salah. Sisa percobaan: {$attemptsLeft}",
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log aktivitas logout sebelum session di-invalidate
        ActivityLogService::logLogout('Admin logout');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
