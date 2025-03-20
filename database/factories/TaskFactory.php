<?php

namespace Database\Factories;

use App\Task\Infrastructure\Enums\TaskStatus;
use App\Task\Infrastructure\Models\Task;
use App\User\Infrastructure\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'status' => $this->faker->randomElement([TaskStatus::PENDING->value, TaskStatus::IN_PROGRESS->value, TaskStatus::COMPLETED->value]),
            'user_id' => User::factory()->create(['email' => fake()->email()]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
