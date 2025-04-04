<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token]);
        }
        return response()->json(['message' => 'Credencials invàlides'], 401);
    }

    public function register(Request $request)
    {
        try {
            // Validació dels camps
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            // Crear l'usuari
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Generar token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token], 201);
        } catch (ValidationException $e) {
            // Retorna errors de validació (com email duplicat) amb codi 422
            return response()->json([
                'message' => 'Error de validació',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Gestiona altres errors (com problemes de BD) amb codi 500
            return response()->json([
                'message' => 'Error al registrar l\'usuari',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
