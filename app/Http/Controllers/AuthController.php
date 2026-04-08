<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        // kalau sudah login, redirect langsung
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // validasi
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // login
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->onlyInput('email');
        }

        // regenerate session (security)
        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user());
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * 🔥 Redirect berdasarkan role (clean & scalable)
     */
    private function redirectByRole($user)
    {
        return match (true) {
            $user->hasRole('admin') => redirect()->route('admin.dashboard'),
            $user->hasRole('gudang') => redirect()->route('gudang.dashboard'),
            $user->hasRole('produksi') => redirect()->route('produksi.dashboard'),
            $user->hasRole('penjualan') => redirect()->route('penjualan.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }
}