<?php

namespace App\Task\Infrastructure\Repositories;

use App\Task\Domain\DTO\CommentDTO;
use App\Task\Domain\DTO\TaskDTO;
use App\Task\Domain\Repositories\TaskRepositoryInterface;
use App\Task\Infrastructure\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskEloquentRepository implements TaskRepositoryInterface
{
    public function all($filters = [], $paginate = 10): LengthAwarePaginator
    {
        return Task::with('comments')->when(! empty($filters), function ($query) use ($filters) {
            foreach ($filters as $column => $value) {
                $query->where($column, 'like', "%$value%");
            }
        })->paginate($paginate);
    }

    public function findById(int $id): ?Task
    {
        return Task::with('comments')->find($id);
    }

    public function save(TaskDTO $task): void
    {
        Task::create($task->all());
    }

    public function update(int $id, TaskDTO $task): void
    {
        Task::findOrFail($id)->update($task->all());
    }

    public function delete(int $id): void
    {
        Task::findOrFail($id)->delete();
    }

    public function saveComment(int $id, CommentDTO $commentDTO): void
    {
        Task::findOrFail($id)->comments()->create($commentDTO->all());
    }
}
