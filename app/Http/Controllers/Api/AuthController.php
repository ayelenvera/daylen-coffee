<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login de usuario y generación de token Sanctum
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'sometimes|string|max:255',
        ]);

        try {
            $deviceName = $request->device_name ?? 'web-browser';
            $result = $this->authService->login($request->only('email', 'password'), $deviceName);
            
            return response()->json([
                'user' => [
                    'id' => $result['user']->id,
                    'name' => $result['user']->name,
                    'email' => $result['user']->email,
                    'is_admin' => $result['user']->is_admin,
                ],
                'token' => $result['token'],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Credenciales inválidas',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Cerrar sesión y revocar token actual
     */
    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }

    /**
     * Obtener usuario autenticado actual
     */
    public function user(Request $request)
    {
        return response()->json($this->authService->getCurrentUser($request));
    }
}