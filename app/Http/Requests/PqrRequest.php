<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PqrRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición
     */
    public function authorize(): bool
    {
        return true; // Cambiar según tus reglas de autorización
    }

    /**
     * Reglas de validación que se aplican a la petición
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'required|string|min:10',
            'responsible' => 'required|string|max:255',
            'dependency' => 'required|exists:dependency_subunits,subunit_id',
            'pdf' => 'nullable|file|mimes:pdf|max:10240', // Máximo 10MB
        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio',
            'title.max' => 'El título no puede exceder los 255 caracteres',
            
            'date.required' => 'La fecha es obligatoria',
            'date.date' => 'La fecha debe ser válida',
            
            'description.required' => 'La descripción es obligatoria',
            'description.min' => 'La descripción debe tener al menos 10 caracteres',
            
            'responsible.required' => 'El responsable es obligatorio',
            'responsible.max' => 'El nombre del responsable no puede exceder los 255 caracteres',
            
            'dependency.required' => 'La dependencia es obligatoria',
            'dependency.max' => 'El nombre de la dependencia no puede exceder los 255 caracteres',
            
            'pdf.file' => 'El archivo debe ser un documento válido',
            'pdf.mimes' => 'El archivo debe ser un PDF',
            'pdf.max' => 'El archivo no puede exceder los 10MB',
        ];
    }

    /**
     * Nombres de atributos personalizados para los mensajes de error
     */
    public function attributes(): array
    {
        return [
            'title' => 'título',
            'date' => 'fecha',
            'description' => 'descripción',
            'responsible' => 'responsable',
            'dependency' => 'dependencia',
            'pdf' => 'archivo PDF',
        ];
    }
}