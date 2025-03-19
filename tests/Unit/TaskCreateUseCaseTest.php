<?php

namespace Tests\Unit;

use App\Task\Application\TaskCreateUseCase;
use App\Task\Domain\DTO\CommentDTO;
use App\Task\Domain\DTO\TaskDTO;
use App\Task\Infrastructure\Models\Task;
use App\Task\Infrastructure\Repositories\TaskEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class TaskCreateUseCaseTest extends TestCase
{
    use RefreshDatabase;
    protected $taskService;
    protected $taskRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = Mockery::mock(TaskEloquentRepository::class);
        $this->taskService = new TaskCreateUseCase($this->taskRepository);
    }

    public function test_create_task(): void
    {
        $taskData = new TaskDTO(...['title' => 'Test Task', 'description' => 'Task description', 'status' => 'pending', 'user_id' => 1]);
        $this->taskRepository->shouldReceive('save')->once()->andReturnNull();

        $this->taskService->createTask($taskData);

        $this->assertTrue(true);
    }

    public function test_update_task(): void
    {
        $taskId = 1;
        $taskData = new TaskDTO(...['title' => 'Test Task', 'description' => 'Task description', 'status' => 'pending', 'user_id' => 1]);

        $this->taskRepository->shouldReceive('update')->once()->with($taskId, $taskData)->andReturnNull();
        $this->taskRepository->shouldReceive('findById')->once()->with($taskId)->andReturn(Mockery::mock(Task::class));

        $this->taskService->updateTask($taskId, $taskData);

        $this->assertTrue(true);
    }

    public function test_update_task_fail(): void
    {
        $taskId = 1;
        $taskData = new TaskDTO(...['title' => 'Test Task', 'description' => 'Task description', 'status' => 'pending', 'user_id' => 1]);

        $this->expectException(ValidationException::class);
        $this->taskRepository->shouldReceive('findById')->once()->with($taskId)->andReturnNull();

        $this->taskService->updateTask($taskId, $taskData);

        $this->assertTrue(true);
    }

    public function test_add_comment(): void
    {
        $taskId = 1;
        $commentData = new CommentDTO(...['content' => 'Test comment']);

        $this->taskRepository->shouldReceive('saveComment')->once()->with($taskId, $commentData)->andReturnNull();
        $this->taskRepository->shouldReceive('findById')->once()->with($taskId)->andReturn(Mockery::mock(Task::class));

        $this->taskService->addComment($taskId, $commentData);

        $this->assertTrue(true);
    }

    public function test_add_comment_fail(): void
    {
        $taskId = 1;
        $commentData = new CommentDTO(...['content' => 'Test comment']);

        $this->expectException(ValidationException::class);
        $this->taskRepository->shouldReceive('findById')->once()->with($taskId)->andReturnNull();

        $this->taskService->addComment($taskId, $commentData);

        $this->assertTrue(true);
    }
}
