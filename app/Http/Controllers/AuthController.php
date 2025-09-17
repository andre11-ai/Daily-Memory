<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'admin') {
                return redirect('/admin');
            } else {
                return redirect('/menu');
            }
        }
        return back()->withErrors(['username' => 'Credenciales incorrectas']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'appe' => 'required|string|max:40',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|min:4|max:16|unique:users,username',
            'password' => 'required|string|min:8|max:32|confirmed',
        ]);
        $user = User::create([
            'name' => $request->name,
            'appe' => $request->appe,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'user', 
        ]);
        Auth::login($user);
        return redirect('/menu');
    }
}
