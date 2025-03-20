<?php

namespace App\User\Infrastructure\Repositories;

use App\User\Domain\DTO\UserUpdateDTO;
use App\User\Infrastructure\Models\User;
use App\User\Domain\Repositories\UserRepositoryInterface;
use App\User\Domain\DTO\UserOutputDTO;
use App\User\Domain\DTO\UserCreateDTO;
use Illuminate\Pagination\LengthAwarePaginator;


class UserEloquentRepository implements UserRepositoryInterface
{
    public function all($filters = [], $paginate = 10): ?LengthAwarePaginator
    {
        return User::when(!empty($filters), function ($query) use ($filters) {
            foreach ($filters as $column => $value) {
                $query->where($column, 'like', "%$value%");
            }
        })->paginate($paginate);
    }

    public function findById(int $id): ?UserOutputDTO
    {
        $user = User::where('id', $id)->first();

        if ($user === null) {
            return null;
        }

        return new UserOutputDTO($user->name, $user->email, $user->userable_type, $user->userable_id);
    }

    public function findByEmail(string $email): ?UserOutputDTO
    {
        $user = User::where('email', $email)->first();

        if ($user === null) {
            return null;
        }

        return new UserOutputDTO($user->name, $user->email, $user->userable_type, $user->userable_id);
    }

    public function save(UserCreateDTO $user): void
    {
        User::create($user->all());
    }

    public function update(int $id, UserUpdateDTO $user): void
    {
        User::findOrFail($id)->update($user->all());
    }

    public function delete(int $id): void
    {
        User::findOrFail($id)->delete();
    }
}
