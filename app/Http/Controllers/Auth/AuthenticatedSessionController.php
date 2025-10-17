<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
//use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
    
        $request->session()->regenerate();
    
        // ✅ SOLUCIÓN CON URLs DIRECTAS ABSOLUTAS
        if (auth()->user()->is_admin) {
            return Inertia::location('/admin/dashboard');
        }

        // Para usuarios normales - URL absoluta
        return Inertia::location('/home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Limpiar también tokens de Sanctum si existen
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        // ✅ SOLUCIÓN: Inertia::location con URL directa
        return Inertia::location('/home');
    }
}