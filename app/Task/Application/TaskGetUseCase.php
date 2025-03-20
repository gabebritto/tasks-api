<?php

namespace App\Task\Application;

use App\Task\Domain\DTO\TaskOutputDTO;
use App\Task\Domain\Repositories\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskGetUseCase
{
    public function __construct(private TaskRepositoryInterface $taskRepository) {}

    public function getAllTask($filters = [], $paginate = 10): LengthAwarePaginator
    {
        $tasks = $this->taskRepository->all($filters, $paginate);
        $tasks->transform(function ($task) {
            return new TaskOutputDTO(
                id: $task->id,
                title: $task->title,
                description: $task->description,
                status: $task->status,
                created_at: $task->created_at,
                comments: $task->comments->toArray()
            );
        });

        return $tasks;
    }

    public function getTaskById(int $id): ?TaskOutputDTO
    {
        $task = $this->taskRepository->findById($id);

        if ($task === null) {
            return null;
        }

        return new TaskOutputDTO(
            id: $task->id,
            title: $task->title,
            description: $task->description,
            status: $task->status,
            created_at: $task->created_at,
            comments: $task->comments->toArray()
        );
    }
}
