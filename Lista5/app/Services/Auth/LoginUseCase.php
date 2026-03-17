<?php

namespace App\Services\Auth;

use App\Domain\Users\Interfaces\UserRepositoryInterface;
use App\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Hash;

class LoginUseCase
{
    public function __construct(private UserRepositoryInterface $user_repository) {}

    public function execute(array $credentials): array
    {
        $user = $this->user_repository->findByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new InvalidCredentialsException('Credenciais Inválidas.');
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }
}
