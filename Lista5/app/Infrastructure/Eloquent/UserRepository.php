<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\Users\Interfaces\UserRepositoryInterface;
use App\Exceptions\RepositoryException;
use App\Models\User;
use Illuminate\Database\QueryException;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        try {
            return User::create($data);
        } catch (QueryException) {
            throw new RepositoryException('Falha ao criar o usuário.');
        }
    }

    public function findByEmail(string $email): ?User
    {
        try {
            $user = User::where('email', $email)->first();
            if (! $user) {
                return null;
            }

            return $user;
        } catch (QueryException) {
            throw new RepositoryException('Falha ao encontrar o email.');
        }
    }

    public function findById(string $id): ?User
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return null;
            }

            return $user;
        } catch (QueryException) {
            throw new RepositoryException('Falha ao encontrar o usuário.');
        }
    }
}
