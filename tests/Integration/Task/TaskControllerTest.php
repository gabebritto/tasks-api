<?php

namespace Tests\Integration\Task;

use App\Task\Infrastructure\Models\Task;
use App\User\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email' => fake()->email(), 'password' => 'password']);
    }

    public function test_task_list(): void
    {
        Task::factory(5)->create();

        // Send GET request to retrieve all data
        $response = $this->actingAs($this->user)->getJson('/api/task', ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data.0.data');
    }

    public function test_task_get_by_id(): void
    {
        $task = Task::factory()->create();
        $response = $this->actingAs($this->user)->getJson("/api/task/$task->id", ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        // Check if the response has expected data
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => ['task' => ['title', 'description', 'status']]
        ]);
    }

    public function test_task_save(): void
    {
        $payload = [
            'title' => "Name",
            'description' => "test description",
            'status' => "pending"
        ];

        $response = $this->actingAs($this->user)->post("api/task", $payload, ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        // Check if the response has expected data
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
    }

    public function test_task_save_comment(): void
    {
        $taskToBeCommented = Task::factory()->create();
        $payload = [
            'content' => 'comment'
        ];

        $response = $this->actingAs($this->user)->post("api/task/$taskToBeCommented->id/comment", $payload, ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        // Check if the response has expected data
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
    }

    public function test_task_update(): void
    {
        $taskToBeUpdated = Task::factory()->create();
        $payload = [
            'title' => 'updated name',
            'description' => 'updated description',
            'status' => 'in_progress'
        ];

        $response = $this->actingAs($this->user)->put("api/task/$taskToBeUpdated->id", $payload, ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        // Check if the response has expected data
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
    }

    public function test_task_delete(): void
    {
        $taskToBeDeleted = Task::factory()->create();
        $response = $this->actingAs($this->user)->delete("api/task/$taskToBeDeleted->id", ['Accept' => 'application/json']);

        // Do the assertions
        $response->assertStatus(200);
        // Check if the response has expected data
        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);

        $this->assertDatabaseMissing('users', ['id' => $taskToBeDeleted->id]);
    }
}
