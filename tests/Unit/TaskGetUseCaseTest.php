<?php

namespace Tests\Unit;

use App\Task\Application\TaskGetUseCase;
use App\Task\Domain\DTO\TaskOutputDTO;
use App\Task\Infrastructure\Models\Task;
use App\Task\Infrastructure\Repositories\TaskEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class TaskGetUseCaseTest extends TestCase
{
    use RefreshDatabase;
    protected $taskService;
    protected $taskRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = Mockery::mock(TaskEloquentRepository::class);
        $this->taskService = new TaskGetUseCase($this->taskRepository);
    }

    public function test_get_all_task(): void
    {
        $tasks = collect([
            (object)[
                'id' => 1,
                'title' => 'Task 1',
                'description' => 'Description for task 1',
                'status' => 'pending',
                'created_at' => now(),
                'comments' => collect([]),
            ],
            (object)[
                'id' => 2,
                'title' => 'Task 2',
                'description' => 'Description for task 2',
                'status' => 'completed',
                'created_at' => now(),
                'comments' => collect([]),
            ]
        ]);

        $mockPaginator = Mockery::mock(LengthAwarePaginator::class);
        $mockPaginator->shouldReceive('transform')->andReturn($tasks);
        $mockPaginator->shouldReceive('items')->andReturn($tasks);

        $this->taskRepository->shouldReceive('all')->once()->with([], 10)->andReturn($mockPaginator);

        $result = $this->taskService->getAllTask();

        $this->assertCount(2, $result->items());
    }

    public function test_get_task_by_id(): void
    {
        $taskId = 1;
        $mockTask = Mockery::mock(Task::class);
        $mockTask->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $mockTask->shouldReceive('getAttribute')->with('title')->andReturn('Test Task');
        $mockTask->shouldReceive('getAttribute')->with('description')->andReturn('Test Description');
        $mockTask->shouldReceive('getAttribute')->with('status')->andReturn('open');
        $mockTask->shouldReceive('getAttribute')->with('created_at')->andReturn(now());
        $mockTask->shouldReceive('getAttribute')->with('comments')->andReturn(collect([]));

        $this->taskRepository->shouldReceive('findById')->once()->with($taskId)->andReturn($mockTask);

        $result = $this->taskService->getTaskById($taskId);

        $this->assertInstanceOf(TaskOutputDTO::class, $result);
        $this->assertEquals($mockTask->id, $result->id);
        $this->assertEquals($mockTask->title, $result->title);
        $this->assertEquals($mockTask->description, $result->description);
        $this->assertEquals($mockTask->status, $result->status);
        $this->assertEquals($mockTask->created_at, $result->created_at);
        $this->assertEquals($mockTask->comments->toArray(), $result->comments);
    }

    public function test_get_task_by_id_fail(): void
    {
        $taskId = 9999;

        $this->taskRepository->shouldReceive('findById')->once()->with($taskId)->andReturnNull();

        $result = $this->taskService->getTaskById($taskId);

        $this->assertNull($result);
    }
}
