<?php

namespace App\Http\Modules\TipoImagen\Repositories;

use App\Http\Modules\TipoImagen\Models\TipoImagen;

class TipoImagenRepository
{
    public function listarTipoImagenes()
    {
        return TipoImagen::get();
    }

    public function obtenerTipoImagenPorId(int $id)
    {
        return TipoImagen::where('id', $id)->first();
    }
}
