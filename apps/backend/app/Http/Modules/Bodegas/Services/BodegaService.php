<?php

namespace App\Http\Modules\Bodegas\Services;

use App\Http\Modules\Bodegas\Models\Bodegas;
use App\Http\Modules\Bodegas\Repositories\BodegaRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BodegaService
{
    public function __construct(
        protected BodegaRepository $bodegaRepository
    ) {}

    public function crearBodega(array $data)
    {
        $data = $this->normalizarDatosFormulario($data);

        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255', 'unique:bodegas,nombre'],
            'codigo' => ['nullable', 'string', 'max:80', 'unique:bodegas,codigo'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'estado' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $datosValidados = $validator->validated();
        $datosValidados['estado'] = $datosValidados['estado'] ?? true;

        return Bodegas::create($datosValidados);
    }

    public function actualizarBodega(int $id, array $data)
    {
        $data = $this->normalizarDatosFormulario($data);

        $bodega = $this->bodegaRepository->obtenerBodegaPorId($id);

        if (! $bodega) {
            throw new Exception('Bodega no encontrada');
        }

        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('bodegas', 'nombre')->ignore($id)],
            'codigo' => ['nullable', 'string', 'max:80', Rule::unique('bodegas', 'codigo')->ignore($id)],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'estado' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $bodega->update($validator->validated());

        return $bodega;
    }

    private function normalizarDatosFormulario(array $data): array
    {
        foreach (['codigo', 'ubicacion', 'descripcion'] as $campo) {
            if (array_key_exists($campo, $data) && $data[$campo] === '') {
                $data[$campo] = null;
            }
        }

        return $data;
    }
}
