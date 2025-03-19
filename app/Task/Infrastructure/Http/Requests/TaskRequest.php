<?php

namespace App\Task\Infrastructure\Http\Requests;

use App\Task\Infrastructure\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['required'],
            'status' => ['required', Rule::in([TaskStatus::PENDING, TaskStatus::IN_PROGRESS, TaskStatus::COMPLETED])],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
