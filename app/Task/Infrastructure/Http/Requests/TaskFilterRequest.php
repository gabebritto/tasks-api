<?php

namespace App\Task\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(['PENDING', 'IN_PROGRESS', 'COMPLETED'])],
            'created_at' => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
