<?php

namespace App\Http\Modules\Bodegas\Repositories;

use App\Http\Modules\Bodegas\Models\Bodegas;

class BodegaRepository
{
    public function listarBodegas()
    {
        return Bodegas::orderBy('id', 'desc')->get();
    }

    public function obtenerBodegaPorId(int $id)
    {
        return Bodegas::find($id);
    }
}
