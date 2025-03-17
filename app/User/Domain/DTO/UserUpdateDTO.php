<?php

namespace App\User\Domain\DTO;

use Illuminate\Contracts\Validation\Validator;
use App\Shared\Domain\DTO\AbstractDTO;
use App\Shared\Domain\DTO\InterfaceDTO;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserUpdateDTO extends AbstractDTO implements InterfaceDTO
{
    public function __construct(
		public string $name,
		public string $email,
        public ?int $group_id = null
	){
        $this->validate();
    }

    public static function rules():array{
        return [
            'name' => 'required|string|min:3|max:60',
            'email' => 'required|email|max:255',
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
        ];
    }

    public function validator(): Validator {
        return validator($this->all(), self::rules(), self::messages());
    }

    public function validate(): array {
        return $this->validator()->validate();
    }

}
