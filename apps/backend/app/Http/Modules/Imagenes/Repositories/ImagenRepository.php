<?php

namespace App\Http\Modules\Imagenes\Repositories;

use App\Http\Modules\Imagenes\Models\Imagenes;

class ImagenRepository
{
    public function listarImagenes()
    {
        return Imagenes::with('tipoImagen')
            ->orderBy('id', 'desc')
            ->get();
    }
}
