<?php

namespace App\User\Application;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\User\Domain\DTO\UserOutputDTO;
use App\User\Domain\Repositories\UserRepositoryInterface;

class UserGetUseCase
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        // Aqui será injetado o repositório independentemente de tecnologia
        $this->userRepository = $userRepository;
    }

    // Aqui devem ser implementadas as regras de negócio

    public function getAllUsers($filters = [], $paginate = 10): ?LengthAwarePaginator
    {
        return $this->userRepository->all($filters, $paginate);
    }

    public function getUserById(int $id): ?UserOutputDTO
    {
        $user = $this->userRepository->findById($id);

        if ($user === null) {
            return null;
        }

        return new UserOutputDTO($user->name, $user->email);
    }

    public function getUserByEmail(string $email): ?UserOutputDTO
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user === null) {
            return null;
        }

        return new UserOutputDTO($user->name, $user->email);
    }

}
