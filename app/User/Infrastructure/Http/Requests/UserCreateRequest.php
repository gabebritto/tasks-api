<?php

namespace App\User\Infrastructure\Http\Requests;

use App\User\Domain\DTO\AdminCreateDTO;
use App\User\Domain\DTO\CustomerCreateDTO;
use App\User\Domain\DTO\UserCreateDTO;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return UserCreateDTO::rules();
    }

    public function messages(): array
    {
        return UserCreateDTO::messages();
    }

    public function authorize(): bool
    {
        return true;
    }
}
