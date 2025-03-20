<?php

namespace App\User\Application;

use App\User\Domain\Repositories\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserDeleteUseCase
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function delete(int $id): void
    {
        try {
            DB::beginTransaction();
            $userExists = $this->userRepository->findById($id);

            if (! $userExists) {
                throw ValidationException::withMessages(['id' => 'O usuário não existe.']);
            }

            $this->userRepository->delete($id);
            DB::commit();
        } catch (ValidationException $validationException) {
            DB::rollBack();
            throw $validationException;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());

            throw ValidationException::withMessages(['default' => 'Falha ao tentar excluír o Usuário. Se o problema persistir, contate o suporte.']);
        }
    }
}
