<?php

namespace App\Http\Modules\Marcas\Services;

use App\Http\Modules\Marcas\Models\Marcas;
use App\Http\Modules\Marcas\Repositories\MarcaRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MarcaService
{
    public function __construct(
        protected MarcaRepository $marcaRepository
    ) {}

    public function crearMarca(array $data)
    {
        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255', 'unique:marcas,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'estado' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $datosValidados = $validator->validated();
        $datosValidados['estado'] = $datosValidados['estado'] ?? true;

        return Marcas::create($datosValidados);
    }

    public function actualizarMarca(int $id, array $data)
    {
        $marca = $this->marcaRepository->obtenerMarcaPorId($id);

        if (! $marca) {
            throw new Exception('Marca no encontrada');
        }

        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('marcas', 'nombre')->ignore($id)],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'estado' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $marca->update($validator->validated());

        return $marca;
    }
}
