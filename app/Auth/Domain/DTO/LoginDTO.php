<?php

namespace App\Auth\Domain\DTO;

use App\Shared\Domain\DTO\AbstractDTO;

class LoginDTO extends AbstractDTO
{
    public function __construct(
        public string $email,
        public string $password
    ) {}
}
