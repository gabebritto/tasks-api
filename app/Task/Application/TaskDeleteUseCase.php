<?php

namespace App\Task\Application;

use Exception;
use App\Task\Domain\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TaskDeleteUseCase
{
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function delete(int $id): void
    {
        try {
            $taskExists = $this->taskRepository->findById($id);

            if (!$taskExists) {
                throw ValidationException::withMessages(['id' => 'The task not exists.']);
            }

            $this->taskRepository->delete($id);
        } catch (ValidationException $validationException) {
            throw $validationException;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            throw ValidationException::withMessages(['default' => 'Fail to delete the Task.']);
        }
    }
}
