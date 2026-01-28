<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
{
    $userBeingEdited = $this->route('user');
    
    return auth()->id() === $userBeingEdited->id || 
           auth()->user()->hasPermissionTo('edit user'); 
}

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($this->route('user')->id)
            ],
            'role' => ['nullable', 'string', 'exists:roles,name']
        ];
    }
}
