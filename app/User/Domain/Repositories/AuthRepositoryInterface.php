<?php

namespace App\User\Domain\Repositories;

use App\User\Domain\DTO\UserOutputDTO;

interface AuthRepositoryInterface
{
    public function authenticate(string $email, string $password): ?UserOutputDTO;

    public function checkAuth(UserOutputDTO $user): void;

    public function getUserAcls(string $email): ?UserOutputDTO;

    public function deleteTokens(UserOutputDTO $user): void;

    public function getToken(UserOutputDTO $user): ?string;
}
