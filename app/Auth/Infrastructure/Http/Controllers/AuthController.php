<?php

namespace App\Auth\Infrastructure\Http\Controllers;

use App\Auth\Application\AuthUseCase;
use App\Auth\Domain\DTO\LoginDTO;
use App\Auth\Infrastructure\Http\Requests\LoginRequest;
use App\Shared\Infrastructure\Http\Controllers\Controller;
use App\Shared\Infrastructure\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use HttpResponses;

    public function __construct(private AuthUseCase $authUseCase) {}

    /**
     * @unauthenticated
     **/
    public function login(LoginRequest $request): JsonResponse
    {
        $loginDTO = new LoginDTO(
            ...$request->only([
                'email',
                'password',
            ])
        );
        $token = $this->authUseCase->login($loginDTO);

        return $this->response('Authorized', Response::HTTP_OK, ['token' => $token]);
    }

    public function logout(): JsonResponse
    {
        $this->authUseCase->logout();

        return $this->response('Token Revoked', Response::HTTP_OK);
    }

    public function token(Request $request): JsonResponse
    {
        $tokenData = $this->authUseCase->getToken();

        return $this->response('Token Data', Response::HTTP_OK, ['tokenable' => $tokenData, 'bearer' => $request->bearerToken()]);
    }
}
