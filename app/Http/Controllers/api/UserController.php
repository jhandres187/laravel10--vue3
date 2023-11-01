<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('myapptoken')->plainTextToken;
            session()->put('token', $token);
            return response()->json(['token' => $token]);
        }
        return response()->json(['message' => 'Usuario y Contraseña inválidos'], 401);
    }

    public function logout() {
        session()->flush();
        return response()->json('Sesión cerrada exitosamente', 200);
    }
}
