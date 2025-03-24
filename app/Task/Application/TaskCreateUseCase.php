<?php

namespace App\Task\Application;

use App\Task\Domain\DTO\CommentDTO;
use App\Task\Domain\DTO\TaskDTO;
use App\Task\Domain\Repositories\TaskRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TaskCreateUseCase
{
    public function __construct(private TaskRepositoryInterface $taskRepository) {}

    public function createTask(TaskDTO $taskDTO): void
    {
        try {
            $this->taskRepository->save($taskDTO);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            throw ValidationException::withMessages(['default' => 'Fail to create the Task.']);
        }
    }

    public function updateTask(int $id, TaskDTO $taskDTO): void
    {
        try {
            $taskExists = $this->taskRepository->findById($id);

            if (! $taskExists) {
                throw ValidationException::withMessages(['id' => 'The task not exists.']);
            }

            $this->taskRepository->update($id, $taskDTO);
        } catch (ValidationException $validationException) {
            throw $validationException;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            throw ValidationException::withMessages(['default' => 'Fail to update the Task.']);
        }
    }

    public function addComment(int $id, CommentDTO $commentDTO): void
    {
        try {
            $taskExists = $this->taskRepository->findById($id);

            if (! $taskExists) {
                throw ValidationException::withMessages(['id' => 'The task not exists.']);
            }

            $this->taskRepository->saveComment($id, $commentDTO);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            throw ValidationException::withMessages(['default' => 'Fail to create the Task.']);
        }
    }
}
