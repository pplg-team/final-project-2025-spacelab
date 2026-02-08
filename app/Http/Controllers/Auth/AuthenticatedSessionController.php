<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $title = __('Masuk');
        $description = __('Lanjutkan ke akun anda');
        return view('auth.login', ['title' => $title, 'description' => $description]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        User::where('id', Auth::id())->update([
            'last_login_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'pengguna (' . Auth::user()->name . ')',
            'record_id' => Auth::id(),
            'action' => 'login',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' masuk ke sistem pada ' . now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        $request->session()->regenerate();

        return redirect()->intended(route('redirect', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        AuditLog::create([
            'user_id' => Auth::id(),
            'entity' => 'pengguna (' . Auth::user()->name . ')',
            'record_id' => Auth::id(),
            'action' => 'logout',
            'new_data' => [
                'message' => 'Pengguna ' . Auth::user()->name . ' keluar dari sistem pada ' . now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
