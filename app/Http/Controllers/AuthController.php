<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_anak' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'nama_anak' => $request->nama_anak,
            'tanggal_lahir' => $request->tanggal_lahir,
            // keep nik_anak nullable in DB but we no longer require it on register
            'password' => Hash::make($request->password),
            'role' => 'orangtua', 
        ]);

        return redirect('/login')->with('success', 'Register berhasil, silakan login.');
    }

    public function login(Request $request)
    {
        // Backwards-compatible: try to authenticate using nama_anak first; if that fails,
        // attempt using nik_anak with the same input (so existing accounts with NIK still work).
        $identifier = $request->input('nama_anak');
        $password = $request->input('password');

        $attemptByName = Auth::attempt(['nama_anak' => $identifier, 'password' => $password]);
        $attemptByNik = false;
        if (! $attemptByName) {
            $attemptByNik = Auth::attempt(['nik_anak' => $identifier, 'password' => $password]);
        }

        if ($attemptByName || $attemptByNik) {
            $user = Auth::user();

            // Jika ingin pakai role:
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'orangtua') {
                return redirect()->route('orangtua.dashboard');
            } else {
                Auth::logout();
                return redirect('/login')->with('error', 'Role tidak dikenali');
            }
            
        }

        return back()->withErrors([
            'login' => 'Nama Anak atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

}
