<?php

namespace App\Http\Modules\Marcas\Repositories;

use App\Http\Modules\Marcas\Models\Marcas;

class MarcaRepository
{
    public function listarMarcas()
    {
        return Marcas::orderBy('id', 'desc')->get();
    }

    public function obtenerMarcaPorId(int $id)
    {
        return Marcas::find($id);
    }
}
