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
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'orangtua', 
        ]);

        return redirect('/login')->with('success', 'Register berhasil, silakan login.');
    }

    public function login(Request $request)
    {
        $identifier = $request->input('username');
        $password = $request->input('password');

        $attemptByUsername = Auth::attempt(['username' => $identifier, 'password' => $password]);
        
        if ($attemptByUsername) {
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
            'login' => 'Username atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

}
