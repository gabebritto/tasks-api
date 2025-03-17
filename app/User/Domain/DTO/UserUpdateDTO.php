<?php

namespace App\User\Domain\DTO;

use App\Shared\Domain\DTO\AbstractDTO;

class UserUpdateDTO extends AbstractDTO
{
    public function __construct(
		public string $name,
		public string $email,
	){
        parent::__construct($this->all());
    }
}
