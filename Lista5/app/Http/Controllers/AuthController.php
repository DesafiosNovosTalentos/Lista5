<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginAuthRequest;
use App\Http\Requests\Auth\RegisterAuthRequest;
use App\Services\Auth\LoginUseCase;
use App\Services\Auth\LogoutUseCase;
use App\Services\Auth\RegisterUseCase;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterAuthRequest $request, RegisterUseCase $use_case)
    {
        $data = $request->validated();
        $response = $use_case->execute($data);

        return response()->json($response, 201);
    }

    public function login(LoginAuthRequest $request, LoginUseCase $use_case)
    {
        $data = $request->validated();
        $response = $use_case->execute($data);

        return response()->json($response, 200);
    }

    public function logout(Request $request, LogoutUseCase $use_case,)
    {
        $use_case->execute($request->user());

        return response()->json(null, 204);
    }
}
