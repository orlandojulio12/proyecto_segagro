<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PqrRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',

            // Fecha con hora, minuto y segundo
            'date' => 'required|date_format:Y-m-d\TH:i',

            'description' => 'required|string|min:10',
            'responsible' => 'required|string|max:255',
            'concepto_id' => 'required|exists:concepto_pqr,id_concepto',

            // Tutela: cualquier valor numérico entero
            'is_tutela' => 'nullable|boolean',
            'horas_tutela' => 'nullable|integer|min:1|required_if:is_tutela,1',

            // PDF
            'pdf' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio',
            'title.max' => 'El título no puede exceder los 255 caracteres',

            'date.required' => 'La fecha es obligatoria',
            /* 'date.date_format' => 'La fecha debe tener el formato: año-mes-día hora:minuto:segundo (Y-m-d H:i:s)', */

            'description.required' => 'La descripción es obligatoria',
            'description.min' => 'La descripción debe tener al menos 10 caracteres',

            'responsible.required' => 'El responsable es obligatorio',
            'responsible.max' => 'El nombre del responsable no puede exceder los 255 caracteres',

            'concepto_id.required' => 'Debes seleccionar un concepto',
            'concepto_id.exists' => 'El concepto seleccionado no es válido',

            'horas_tutela.required_if' => 'Debes indicar el tiempo de respuesta para la tutela',
            'horas_tutela.integer' => 'El tiempo de tutela debe ser un número entero',
            'horas_tutela.min' => 'El tiempo de tutela debe ser al menos 1 hora',

            'pdf.file' => 'El archivo debe ser un documento válido',
            'pdf.mimes' => 'El archivo debe ser un PDF',
            'pdf.max' => 'El archivo no puede exceder los 5MB',
        ];
    }

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

    protected function prepareForValidation()
    {
        $this->merge([
            'is_tutela' => $this->has('is_tutela') ? 1 : 0,
        ]);
    }
}