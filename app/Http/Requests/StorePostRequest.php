<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
        public function authorize(): bool
    {
        return auth()->check(); 
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:600',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio',
            'description.max' => 'La descripción no puede tneer más de 600 carácteres',
        ];
    }
}
