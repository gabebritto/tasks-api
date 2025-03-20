<?php

namespace App\Task\Domain\Repositories;

use App\Task\Domain\DTO\CommentDTO;
use App\Task\Domain\DTO\TaskDTO;
use App\Task\Infrastructure\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function all($filters, $paginate): LengthAwarePaginator;

    public function findById(int $id): ?Task;

    public function save(TaskDTO $task): void;

    public function saveComment(int $id, CommentDTO $commentDTO): void;

    public function update(int $id, TaskDTO $task): void;

    public function delete(int $id): void;
}
