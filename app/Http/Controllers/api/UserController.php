<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('myapptoken')->plainTextToken;
            session()->put('token', $token);
            return response()->json([
                'isLoggedIn' => true,
                'user' => auth()->user(),
                'token' => $token
            ]);
        }
        return response()->json(['message' => 'Usuario y Contraseña inválidos'], 401);
    }

    public function logout() {
        [$id, $token] = explode('|', request('token'));
        if(Auth::user()){
            Auth::user()->tokens()->where('id', $id)->delete();
        }else{
            PersonalAccessToken::where('id', $id)->delete();
        }
        session()->flush();//cuando se trabaja con laravel sanctum sesion
        return response()->json('Sesión cerrada exitosamente', 200);
    }

    public function checkToken(){
        try {
            [$id, $token] = explode('|', request('token'));
            $tokenHash = hash('sha256', $token);
            $tokenModel = PersonalAccessToken::where('token', $tokenHash)->first();
            if($tokenModel){
                Auth::login($tokenModel->tokenable);
                return response()->json([
                    'isLoggedIn' => true,
                    'user' => auth()->user(),
                    'token' => request('token')
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response()->json(['message' => 'Usuario y Contraseña inválidos'], 401);
    }
}
