<?php

namespace App\Task\Domain\DTO;

use App\Shared\Domain\DTO\AbstractDTO;
use Illuminate\Http\Request;

class TaskOutputDTO extends AbstractDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $status,
        public string $created_at,
        public array $comments
    ) {
        parent::__construct($this->all());
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'comments' => $this->comments
        ];
    }
}
