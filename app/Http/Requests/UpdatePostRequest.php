<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = $this->route('post');

        return auth()->id() === $post->user_id || auth()->user()->can('edit post');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
