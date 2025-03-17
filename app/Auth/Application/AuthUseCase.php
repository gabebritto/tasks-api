<?php

namespace App\Auth\Application;

use App\Auth\Domain\DTO\LoginDTO;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthUseCase
{

    public function login(LoginDTO $credentials)
    {
        if (Auth::attempt($credentials->all())) {
            return Auth::user()->createToken('authToken', [])->plainTextToken;
        }
        throw new UnauthorizedHttpException('', 'Unauthorized');
    }

    public function logout(): void
    {
        Auth::user()->tokens()->delete();
    }

    public function getToken()
    {
        return Auth::user()->tokens;
    }
}
