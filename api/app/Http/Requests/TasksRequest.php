<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TasksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $taskId=$this->route('tasks');
        // Detecta si es update (PATCH/PUT) o store (POST)
        if ($this->isMethod('post')) {
            return [
                'projects_id'   => 'required|exists:projects,id',
                'title'         => 'required|string|max:255',
                'due_date'      => 'nullable|date',
                'is_completed'  => 'required|boolean',
            ];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'projects_id'   => 'sometimes|exists:projects,id',
                'title'         => 'sometimes|string|max:255',
                'due_date'      => 'sometimes|date',
                'is_completed'  => 'sometimes|boolean',
            ];
        }

        return [];
    }

    public function expectsJson(): bool
    {
        return true;
    }

    public function ajax(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'due_date.required' => 'la fecha es obligatoria.',
        ];
    }
}
