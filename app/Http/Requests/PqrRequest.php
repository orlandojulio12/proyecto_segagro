<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PqrRequest extends FormRequest
{
    /**
     * Autorización
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d H:i',
            'description' => 'required|string|min:10',
            'responsible' => 'required|string|max:255',
            'concepto_id' => 'required|exists:concepto_pqr,id_concepto',

            // 🔥 Tutela
            'is_tutela' => 'nullable|boolean',
            'horas_tutela' => 'nullable|integer|in:24,48,72|required_if:is_tutela,1',

            // 📄 PDF
            'pdf' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    /**
     * Mensajes personalizados
     */
    public function messages(): array
    {
        return [
            // Title
            'title.required' => 'El título es obligatorio',
            'title.max' => 'El título no puede exceder los 255 caracteres',

            // Date
            'date.required' => 'La fecha es obligatoria',
            'date.date' => 'La fecha debe ser válida',

            // Description
            'description.required' => 'La descripción es obligatoria',
            'description.min' => 'La descripción debe tener al menos 10 caracteres',

            // Responsable
            'responsible.required' => 'El responsable es obligatorio',
            'responsible.max' => 'El nombre del responsable no puede exceder los 255 caracteres',

            // Concepto
            'concepto_id.required' => 'Debes seleccionar un concepto',
            'concepto_id.exists' => 'El concepto seleccionado no es válido',

            // Tutela
            'horas_tutela.required_if' => 'Debes seleccionar el tiempo de respuesta para la tutela',
            'horas_tutela.in' => 'El tiempo de tutela debe ser 24, 48 o 72 horas',

            // PDF
            'pdf.file' => 'El archivo debe ser un documento válido',
            'pdf.mimes' => 'El archivo debe ser un PDF',
            'pdf.max' => 'El archivo no puede exceder los 5MB',
        ];
    }

    /**
     * Nombres amigables
     */
    public function attributes(): array
    {
        return [
            'title' => 'título',
            'date' => 'fecha',
            'description' => 'descripción',
            'responsible' => 'responsable',
            'concepto_id' => 'concepto',
            'horas_tutela' => 'tiempo de tutela',
            'pdf' => 'archivo PDF',
        ];
    }

    /**
     * Preparación de datos antes de la validación
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_tutela' => $this->has('is_tutela') ? 1 : 0,
        ]);
    }
}