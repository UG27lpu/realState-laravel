<?php

namespace App\Http\Requests\Auth;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'string', 'email', 'max:180', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role'     => ['required', Rule::in([Role::User->value, Role::Agent->value])],
            'agency_name' => ['nullable', 'required_if:role,agent', 'string', 'max:160'],
            'terms'    => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'role.in' => 'Pick a buyer/browser or an agent account.',
            'terms.accepted' => 'Please accept the terms to create an account.',
        ];
    }
}
