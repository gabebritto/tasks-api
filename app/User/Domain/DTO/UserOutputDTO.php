<?php

namespace App\User\Domain\DTO;

use App\Shared\Domain\DTO\AbstractDTO;
use Illuminate\Http\Request;

class UserOutputDTO extends AbstractDTO
{
    public function __construct(
        public string $name,
        public string $email
    ) {
        parent::__construct($this->all());
    }

    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
