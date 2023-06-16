<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(UserRegistrationRequest $request): JsonResponse
    {
        $user = new User($request->validated());
        $user->save();

        return response()->json(data: [
            'message' => 'Registration successful!',
        ], status: 201);
    }
}
