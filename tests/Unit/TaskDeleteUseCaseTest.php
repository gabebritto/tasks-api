<?php

namespace Tests\Unit;

use App\Task\Application\TaskDeleteUseCase;
use App\Task\Domain\DTO\CommentDTO;
use App\Task\Domain\DTO\TaskDTO;
use App\Task\Infrastructure\Models\Task;
use App\Task\Infrastructure\Repositories\TaskEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class TaskDeleteUseCaseTest extends TestCase
{
    use RefreshDatabase;
    protected $taskService;
    protected $taskRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = Mockery::mock(TaskEloquentRepository::class);
        $this->taskService = new TaskDeleteUseCase($this->taskRepository);
    }

    public function test_delete_task(): void
    {
        $taskId = 1;
        $this->taskRepository->shouldReceive('delete')->once()->andReturnNull();
        $this->taskRepository->shouldReceive('findById')->once()->with($taskId)->andReturn(Mockery::mock(Task::class));
        $this->taskService->delete($taskId);

        $this->assertTrue(true);
    }

    public function test_delete_task_fail(): void
    {
        $taskId = 1;
        $this->expectException(ValidationException::class);
        $this->taskRepository->shouldReceive('findById')->once()->with($taskId)->andReturnNull();
        $this->taskService->delete($taskId);

        $this->assertTrue(true);
    }
}
