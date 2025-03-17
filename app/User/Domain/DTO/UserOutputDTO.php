<?php

namespace App\User\Domain\DTO;

use Illuminate\Contracts\Validation\Validator;

use App\Shared\Domain\DTO\AbstractDTO;
use App\Shared\Domain\DTO\InterfaceDTO;
use OpenApi\Annotations as OA;

class UserOutputDTO extends AbstractDTO implements InterfaceDTO
{
    public function __construct(
		public string $name,
		public string $email
	){
        $this->validate();
    }

    public static function rules():array{
        return [
        ];
    }

    public static function messages():array {
        return [
        ];
    }

    public function validator(): Validator {
        return validator($this->all(), self::rules(), self::messages());
    }

    public function validate(): array {
        return $this->validator()->validate();
    }

}
