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
            return User::where('email', $email)->first();
        } catch (QueryException) {
            throw new RepositoryException('Falha ao encontrar o usuário por email.');
        }
    }

    public function findById(string $id): ?User
    {
        try {
            return User::find($id);
        } catch (QueryException) {
            throw new RepositoryException('Falha ao encontrar o usuário.');
        }
    }

    public function findAll(): array
    {
        try {
            return User::all()->toArray();
        } catch (QueryException) {
            throw new RepositoryException('Falha ao listar usuários.');
        }
    }
}
