<?php

namespace App\User\Application;

use App\User\Domain\DTO\UserUpdateDTO;
use Exception;
use App\User\Domain\DTO\UserCreateDTO;
use App\User\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserCreateUseCase
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(UserCreateDTO $userDTO): void
    {
        try {
            DB::beginTransaction();

            $newUserDTO = new UserCreateDTO(
                $userDTO->name,
                $userDTO->email,
                Hash::make($userDTO->password),
                $userDTO->group_id
            );

            $this->userRepository->save($newUserDTO);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());

            throw ValidationException::withMessages(['Falha ao tentar criar o Usuário. Se o problema persistir, contate o suporte.']);
        }
    }

    public function updateUser(int $id, UserUpdateDTO $userDTO): void
    {
        try {
            DB::beginTransaction();

            $userExists = $this->userRepository->findById($id);
            $userEmailExists = $this->userRepository->findByEmail($userDTO->email);

            if (!$userExists) {
                throw ValidationException::withMessages(['id' => 'O usuário não existe.']);
            }

            if ($userEmailExists && $userEmailExists->email !== $userExists->email) {
                throw ValidationException::withMessages(['email' => 'O E-mail já está em uso.']);
            }

            $this->userRepository->update($id, $userDTO);
            DB::commit();
        } catch (ValidationException $validationException) {
            DB::rollBack();
            throw $validationException;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());

            throw ValidationException::withMessages(['default' => 'Falha ao tentar atualizar o Usuário. Se o problema persistir, contate o suporte.']);
        }
    }
}
