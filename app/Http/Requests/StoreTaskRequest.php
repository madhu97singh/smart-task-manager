<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'category_id' => [
                'nullable',
                'exists:categories,id'
            ],

            'priority' => [
                'required',
                Rule::in(['low', 'medium', 'high', 'urgent'])
            ],

            'status' => [
                'required',
                Rule::in(['pending', 'in_progress', 'completed'])
            ],

            'due_date' => ['nullable', 'date'],
        ];
    }
}