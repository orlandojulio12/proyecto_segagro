<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id') ?? $this->route('user')?->id;

        return [
            'name'     => 'required|string|max:255',
            'email'    => "required|string|email|unique:users,email,{$userId}",
            'password' => 'nullable|string|min:8|confirmed',
            'address'  => 'nullable|string|max:225',
            'phone'    => 'nullable|string|max:20',
            'sede_id'  => 'required|exists:sedes,id',
            'role'     => 'nullable|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'Este correo ya está registrado por otro usuario',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ];
    }
}
