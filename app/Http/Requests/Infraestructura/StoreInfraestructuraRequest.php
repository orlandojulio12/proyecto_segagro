<?php

namespace App\Http\Requests\Infraestructura;

use Illuminate\Foundation\Http\FormRequest;

class StoreInfraestructuraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'           => 'required|exists:users,id',
            'unidad_id'         => 'required|exists:dependency_units,dependency_unit_id',
            'subunidad_id'      => 'required|exists:dependency_subunits,subunit_id',
            'inicial_centro_id' => 'required|exists:centros,id',
            'inicial_sede_id'   => 'required|exists:sedes,id',
            'nivel_riesgo'      => 'required|in:1,2,3',
            'tipo_necesidad'    => 'required|string',
            'area_id'           => 'required|exists:areas,id',
            'ambiente'          => 'required|exists:rooms,id',
            'nivel_complejidad' => 'required|in:1,2,3',
            'descripcion'       => 'required|string',
            'motivo_necesidad'  => 'required|string',
            'imagen'            => 'nullable|image|max:2048',
            'requiere_traslado' => 'boolean',
            'final_centro_id'   => 'required_if:requiere_traslado,1|nullable|exists:centros,id',
            'final_sede_id'     => 'required_if:requiere_traslado,1|nullable|exists:sedes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'         => 'El funcionario responsable es obligatorio',
            'unidad_id.required'       => 'La unidad es obligatoria',
            'subunidad_id.required'    => 'La subunidad es obligatoria',
            'inicial_centro_id.required' => 'El centro inicial es obligatorio',
            'inicial_sede_id.required' => 'La sede inicial es obligatoria',
            'nivel_riesgo.in'          => 'El nivel de riesgo debe ser 1, 2 o 3',
            'nivel_complejidad.in'     => 'El nivel de complejidad debe ser 1, 2 o 3',
            'descripcion.required'     => 'La descripción es obligatoria',
            'motivo_necesidad.required'=> 'El motivo de necesidad es obligatorio',
            'final_centro_id.required_if' => 'El centro final es obligatorio si requiere traslado',
            'final_sede_id.required_if'   => 'La sede final es obligatoria si requiere traslado',
        ];
    }
}
