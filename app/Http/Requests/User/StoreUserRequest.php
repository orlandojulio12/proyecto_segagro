<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address'  => 'nullable|string|max:225',
            'phone'    => 'nullable|string|max:20',
            'sede_id'  => 'required|exists:sedes,id',
            'role'     => 'required|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'El nombre es obligatorio',
            'email.required'    => 'El correo es obligatorio',
            'email.unique'      => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed'=> 'Las contraseñas no coinciden',
            'sede_id.required'  => 'La sede es obligatoria',
            'role.required'     => 'El rol es obligatorio',
        ];
    }
}
