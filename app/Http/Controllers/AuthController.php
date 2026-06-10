<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/v1/register',
        tags: ['Autenticación'],
        summary: 'Registrar nuevo usuario',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Juan López'),
                    new OA\Property(property: 'email', type: 'string', example: 'juan@tienda.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Usuario registrado'),
            new OA\Response(response: 422, description: 'Datos inválidos'),
        ]
    )]
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    #[OA\Post(
        path: '/api/v1/login',
        tags: ['Autenticación'],
        summary: 'Iniciar sesión',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'admin@tienda.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Login exitoso'),
            new OA\Response(response: 401, description: 'Credenciales incorrectas'),
        ]
    )]
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credenciales['email'])->first();

        if (!$user || !Hash::check($credenciales['password'], $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    #[OA\Get(
        path: '/api/v1/me',
        tags: ['Autenticación'],
        summary: 'Obtener usuario autenticado',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Usuario autenticado'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ]
    )]
    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'rol'   => $user->rol,
        ]);
    }
}
