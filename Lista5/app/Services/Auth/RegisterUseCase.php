<?php

namespace App\Services\Auth;

use App\Domain\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RegisterUseCase
{
    public function __construct(private UserRepositoryInterface $user_repository) {}

    public function execute(array $data)
    {

        return DB::transaction(function () use ($data) {
            $user = $this->user_repository->create($data);
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ];
        });
    }
}
