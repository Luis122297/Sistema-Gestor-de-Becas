<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        /** @var User $user */
        $user = Auth::user();
        if ($user->two_factor_secret) {

            $user->tokens()->where('name', '2fa_temp')->delete();

            $tempToken = $user->createToken('2fa_temp', ['2fa-verify'], now()->addMinutes(10))->plainTextToken;

            return response()->json([
                'two_factor_required' => true,
                'temp_token'          => $tempToken,
                'token_type'          => 'Bearer',
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Bienvenido ' . $user->name,
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'role'         => $user->role,
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+\-]+@(alumno\.uptex\.edu\.mx|uptex\.edu\.mx)$/i',
            ],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.regex' => 'El sistema es exclusivo para la comunidad UPTEX. Usa tu correo institucional.',
        ]);

        $domain = explode('@', $request->email)[1];
        $role   = ($domain === 'alumno.uptex.edu.mx') ? 'alumno' : 'profesor';

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Usuario registrado exitosamente',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'role'         => $user->role,
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}