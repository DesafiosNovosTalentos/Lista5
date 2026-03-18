<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\LoginAuthRequest;
use App\Http\Requests\Users\RegisterAuthRequest;
use App\Services\Users\ListUsersUseCase;
use App\Services\Users\LoginUseCase;
use App\Services\Users\LogoutUseCase;
use App\Services\Users\RegisterUseCase;
use Illuminate\Http\Request;

class UserController extends Controller
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

    public function logout(Request $request, LogoutUseCase $use_case)
    {
        $use_case->execute($request->user());

        return response()->json(null, 204);
    }

    public function index(ListUsersUseCase $use_case)
    {
        return response()->json($use_case->execute(), 200);
    }
}
