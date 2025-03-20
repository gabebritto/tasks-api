<?php

namespace App\Task\Domain\DTO;

use App\Shared\Domain\DTO\AbstractDTO;
use Illuminate\Http\Request;

class CommentDTO extends AbstractDTO
{
    public function __construct(
        public string $content
    ) {
        parent::__construct($this->all());
    }

    public function toArray(Request $request): array
    {
        return [
            'content' => $this->content,
        ];
    }
}
