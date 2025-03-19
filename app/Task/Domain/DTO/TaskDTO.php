<?php

namespace App\Task\Domain\DTO;

use App\Shared\Domain\DTO\AbstractDTO;
use Illuminate\Http\Request;

class TaskDTO extends AbstractDTO
{
    public int $user_id;

    public function __construct(
        public string $title,
        public string $description,
        public string $status
    ) {
        $this->user_id = auth()->user()->id;

        parent::__construct($this->all());
    }

    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status
        ];
    }
}
