<?php

namespace App\User\Domain\DTO;

use Illuminate\Contracts\Validation\Validator;
use App\Shared\Domain\DTO\AbstractDTO;
use App\Shared\Domain\DTO\InterfaceDTO;
use Illuminate\Support\Str;

class UserCreateDTO extends AbstractDTO implements InterfaceDTO
{
    public string $email_verified_at;
    public string $remember_token;

    public function __construct(
		public string $name,
		public string $email,
		public string $password,
        public ?int $group_id = null
	){
        $this->validate();

        $this->email_verified_at = now();
        $this->remember_token = Str::random(10);
    }

    public static function rules():array{
        return [
            'name' => 'required|string|min:3|max:60',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:255',
            'email_verified_at' => 'date',
            'remember_token' => 'string|max:100',
            'group_id' => 'nullable|int'
        ];
    }

    public static function messages():array{
        return [
            'name.required' => 'Você precisa definir um nome.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 60 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Insira um endereço de e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
            'email.unique' => 'O e-mail já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.max' => 'A senha não pode ter mais de 14 caracteres.',
            'email_verified_at.date' => 'A data de verificação deve ser uma data válida.',
            'remember_token.max' => 'O token de lembrança deve ter no máximo 100 caracteres.'
        ];
    }

    public function validator(): Validator {
        return validator($this->all(), self::rules(), self::messages());
    }

    public function validate(): array {
        return $this->validator()->validate();
    }

}
