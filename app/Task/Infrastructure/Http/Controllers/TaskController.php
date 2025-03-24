<?php

namespace App\Task\Infrastructure\Http\Controllers;

use App\Shared\Infrastructure\Http\Controllers\Controller;
use App\Shared\Infrastructure\Traits\HttpResponses;
use App\Task\Application\TaskCreateUseCase;
use App\Task\Application\TaskDeleteUseCase;
use App\Task\Application\TaskGetUseCase;
use App\Task\Domain\DTO\CommentDTO;
use App\Task\Domain\DTO\TaskDTO;
use App\Task\Infrastructure\Http\Requests\CommentRequest;
use App\Task\Infrastructure\Http\Requests\TaskFilterRequest;
use App\Task\Infrastructure\Http\Requests\TaskRequest;
use App\Task\Infrastructure\Repositories\TaskEloquentRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    use HttpResponses;

    private TaskCreateUseCase $taskCreateUseCase;

    private TaskGetUseCase $taskGetUseCase;

    private TaskDeleteUseCase $taskDeleteUseCase;

    public function __construct(TaskEloquentRepository $taskRepository)
    {
        $this->taskCreateUseCase = new TaskCreateUseCase($taskRepository);
        $this->taskDeleteUseCase = new TaskDeleteUseCase($taskRepository);
        $this->taskGetUseCase = new TaskGetUseCase($taskRepository);
    }

    public function index(TaskFilterRequest $request): JsonResponse
    {
        $allTask = $this->taskGetUseCase->getAllTask($request->validated());

        return $this->response('Success', Response::HTTP_OK, [$allTask]);
    }

    public function show(int $id): JsonResponse
    {
        $task = $this->taskGetUseCase->getTaskById($id);

        if ($task) {
            return $this->response('Success', Response::HTTP_OK, ['task' => $task]);
        }

        return $this->response('Task not found', Response::HTTP_NOT_FOUND);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $taskDTO = new TaskDTO(
            title: $request->title,
            description: $request->description,
            status: $request->status,
            user_id: auth()->user()->id
        );

        $this->taskCreateUseCase->createTask(
            $taskDTO
        );

        return $this->response('Task successfully created!', Response::HTTP_OK);
    }

    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $taskDTO = new TaskDTO(
            title: $request->title,
            description: $request->description,
            status: $request->status,
            user_id: auth()->user()->id
        );

        $this->taskCreateUseCase->updateTask(
            $id,
            $taskDTO
        );

        return $this->response('Task successfully updated!', Response::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->taskDeleteUseCase->delete($id);

        return $this->response('Task successfully deleted!', Response::HTTP_OK);
    }

    public function storeComment(CommentRequest $request, int $id): JsonResponse
    {
        $commentDTO = new CommentDTO(
            ...$request->only([
                'content',
            ])
        );

        $this->taskCreateUseCase->addComment(
            $id,
            $commentDTO
        );

        return $this->response('Comment successfully added!', Response::HTTP_OK);
    }
}
