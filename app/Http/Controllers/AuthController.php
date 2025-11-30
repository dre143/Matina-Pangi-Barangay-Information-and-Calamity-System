<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            AuditLog::logAction(
                'login',
                'User',
                auth()->id(),
                auth()->user()->name . ' logged in'
            );

            $user = auth()->user();
            if (($user->status ?? 'active') === 'deactivated') {
                $app = $user->assigned_app;
                if ($app) {
                    return redirect()->route('apps.' . $app);
                }
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is deactivated. Contact secretary for access.',
                ]);
            }

            if ($user->role === 'staff') {
                return redirect()->route('apps.profiling_only');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        AuditLog::logAction(
            'logout',
            'User',
            auth()->id(),
            auth()->user()->name . ' logged out'
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
