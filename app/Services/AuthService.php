<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login($credentials, $deviceName = 'web')
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Crear token de acceso
        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout($request)
    {
        // Eliminar token actual
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function getCurrentUser($request)
    {
        return [
            'user' => $request->user(),
            'is_admin' => $request->user()->is_admin
        ];
    }
}