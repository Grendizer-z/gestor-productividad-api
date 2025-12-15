<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectsRequest extends FormRequest
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
        // Obtener el método HTTP actual
        $method = $this->method();

        if ($method === 'PATCH' || $method === 'PUT') {
            // Para PATCH/PUT, todos los campos son opcionales (usando 'sometimes')
            return [
                'name' => ['sometimes', 'string', 'max:255'],
                'description' => ['sometimes', 'nullable', 'string'],
                'is_archived' => ['sometimes', 'boolean'],
            ];
        }

        // Reglas para POST (creación)
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_archived' => ['nullable', 'boolean'],
        ];
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
            'description.required' => 'las descripción es obligatorio.',
        ];
    }
}
