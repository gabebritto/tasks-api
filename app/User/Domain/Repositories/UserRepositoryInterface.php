<?php

namespace App\User\Domain\Repositories;

use App\User\Domain\DTO\UserCreateDTO;
use App\User\Domain\DTO\UserOutputDTO;
use App\User\Domain\DTO\UserUpdateDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    // Assinaturas

    public function all($filters, $paginate): ?LengthAwarePaginator;

    public function findById(int $id): ?UserOutputDTO;

    public function findByEmail(string $email): ?UserOutputDTO;

    public function save(UserCreateDTO $user): void;

    public function update(int $id, UserUpdateDTO $user): void;

    public function delete(int $id): void;
}
