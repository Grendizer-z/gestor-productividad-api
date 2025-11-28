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
        $projectId=$this->route('projects');
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:300',
            'is_archived' => 'required|bool|max:300',
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
