<?php

namespace App\Http\Requests\Traslado;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNeedTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unidad_id'               => 'required|exists:dependency_units,dependency_unit_id',
            'subunidad_id'            => 'required|exists:dependency_subunits,subunit_id',
            'centro_inicial_id'       => 'nullable|exists:centros,id',
            'sede_inicial_id'         => 'nullable|exists:sedes,id',
            'centro_final_id'         => 'nullable|exists:centros,id',
            'sede_final_id'           => 'nullable|exists:sedes,id',
            'fecha_inicio'            => 'required|date',
            'fecha_fin'               => 'required|date|after_or_equal:fecha_inicio',
            'descripcion'             => 'nullable|string',
            'nivel_riesgo'            => 'required|string',
            'nivel_complejidad'       => 'required|string',
            'presupuesto_solicitado'  => 'nullable|numeric|min:0',
            'presupuesto_aceptado'    => 'nullable|numeric|min:0',
            'requiere_personal'       => 'boolean',
            'requiere_materiales'     => 'boolean',
            'status'                  => 'required|in:pendiente,completada,cancelada',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'centro_inicial_id' => $this->input('inicial_centro_id'),
            'sede_inicial_id'   => $this->input('inicial_sede_id'),
            'centro_final_id'   => $this->input('final_centro_id'),
            'sede_final_id'     => $this->input('final_sede_id'),
        ]);
    }
}
