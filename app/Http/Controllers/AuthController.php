<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(UserRegistrationRequest $request): JsonResponse
    {
        $user = new User($request->validated());
        $user->save();

        return response()->json(data: [
            'message' => 'Registration successful!',
        ], status: Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = $request->user();

            $user->tokens()->delete();
            $token = $user->createToken('login_token');

            return response()->json(data: [
                'message' => 'Login successful!',
                'token' => $token->plainTextToken
            ]);
        }

        return response()->json(data: [
            'message' => 'Wrong credentials.'
        ], status: Response::HTTP_BAD_REQUEST);
    }
}
