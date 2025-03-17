<?php

namespace App\User\Domain\DTO;

use Illuminate\Contracts\Validation\Validator;
use App\Shared\Domain\DTO\AbstractDTO;
use App\Shared\Domain\DTO\InterfaceDTO;
use Illuminate\Support\Str;

class UserCreateDTO extends AbstractDTO
{
    public string $email_verified_at;
    public string $remember_token;

    public function __construct(
		public string $name,
		public string $email,
		public string $password
	){
        $this->email_verified_at = now();
        $this->remember_token = Str::random(10);

        parent::__construct($this->all());
    }
}
