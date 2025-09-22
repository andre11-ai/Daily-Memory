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
        $request->validate([
            'username' => ['required','string','min:4','max:16','regex:/^[a-zA-Z0-9_]+$/'],
            'password' => ['required','string','min:8','max:32'],
        ],[
            'username.required' => 'El usuario es obligatorio.',
            'username.regex' => 'Solo letras, números y guion bajo.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
        ]);

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
            'name' => ['required','string','max:20','regex:/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/'],
            'appe' => ['required','string','max:40','regex:/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/'],
            'email' => 'required|email|unique:users,email',
            'username' => ['required','string','min:4','max:16','regex:/^[a-zA-Z0-9_]+$/','unique:users,username'],
            'password' => ['required','string','min:8','max:32','confirmed'],
        ],[
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'Solo letras y espacios.',
            'appe.required' => 'El apellido es obligatorio.',
            'appe.regex' => 'Solo letras y espacios.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Correo no válido.',
            'email.unique' => 'Correo ya registrado.',
            'username.required' => 'El usuario es obligatorio.',
            'username.regex' => 'Solo letras, números y guion bajo.',
            'username.unique' => 'Usuario ya registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
        ]);
        $user = User::create([
            'name' => $request->name,
            'appe' => $request->appe,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect('/login'); 
    }
}
