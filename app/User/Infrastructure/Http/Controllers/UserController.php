<?php

namespace App\User\Infrastructure\Http\Controllers;

use App\Shared\Infrastructure\Http\Controllers\Controller;
use App\Shared\Infrastructure\Traits\HttpResponses;
use App\User\Application\UserCreateUseCase;
use App\User\Application\UserDeleteUseCase;
use App\User\Application\UserGetUseCase;
use App\User\Domain\DTO\UserCreateDTO;
use App\User\Domain\DTO\UserUpdateDTO;
use App\User\Infrastructure\Http\Requests\UserCreateRequest;
use App\User\Infrastructure\Http\Requests\UserUpdateRequest;
use App\User\Infrastructure\Repositories\UserEloquentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use HttpResponses;
    private UserCreateUseCase $userCreateUseCase;
    private UserGetUseCase $userGetUseCase;
    private UserDeleteUseCase $userDeleteUseCase;

    public function __construct(UserEloquentRepository $userRepository)
    {
        // Injetando o repositorio eloquent no Service / UseCase
        $this->userCreateUseCase = new UserCreateUseCase($userRepository);
        $this->userDeleteUseCase = new UserDeleteUseCase($userRepository);
        $this->userGetUseCase = new UserGetUseCase($userRepository);
    }

    public function index(Request $request): JsonResponse
    {
        $allUsers = $this->userGetUseCase->getAllUsers($request->except('paginate'), $request->paginate);
        return response()->json($allUsers);
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userGetUseCase->getUserById($id);

        if ($user) {
            return $this->response("Operação realizada com sucesso", Response::HTTP_OK, ['user' =>  $user]);
        }

        return $this->response("Usuário não encontrado", Response::HTTP_NOT_FOUND);
    }

    public function getUserByEmail(Request $request): JsonResponse
    {
        $user = $this->userGetUseCase->getUserByEmail($request['email']);

        if ($user) {
            return $this->response("Operação realizada com sucesso", Response::HTTP_OK, ['user' =>  $user]);
        }

        return $this->response("Usuário não encontrado", Response::HTTP_NOT_FOUND);
    }

    public function store(UserCreateRequest $request): JsonResponse
    {
        $userDTO = new UserCreateDTO(
            ...$request->only([
                "name",
                "email",
                "password",
                "group_id"
            ])
        );

        $this->userCreateUseCase->createUser(
            $userDTO
        );

        return $this->response("Usuário cadastrado com sucesso!", Response::HTTP_OK);
    }

    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $userDTO = new UserUpdateDTO(
            ...$request->only([
                "name",
                "email",
                "group_id"
            ])
        );

        $this->userCreateUseCase->updateUser(
            $id,
            $userDTO
        );

        return $this->response("Usuário atualizado com sucesso!", Response::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userDeleteUseCase->delete($id);

        return $this->response("Usuário excluído com sucesso!", Response::HTTP_OK);
    }
}
