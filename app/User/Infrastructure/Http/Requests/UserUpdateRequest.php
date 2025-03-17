<?php

namespace App\User\Infrastructure\Http\Requests;

use App\User\Domain\DTO\AdminCreateDTO;
use App\User\Domain\DTO\CustomerCreateDTO;
use App\User\Domain\DTO\CustomerUpdateDTO;
use App\User\Domain\DTO\UserCreateDTO;
use App\User\Domain\DTO\UserUpdateDTO;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return UserUpdateDTO::rules();
    }

    public function messages(): array
    {
        return UserUpdateDTO::messages();
    }

    public function authorize(): bool
    {
        return true;
    }
}
