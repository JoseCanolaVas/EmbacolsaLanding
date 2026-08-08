<?php

namespace App\Http\Modules\Categorias\Repositories;
use App\Http\Modules\Categorias\Models\Categorias;

class CategoriaRepository
{
    public function listarCategorias()
    {
        return Categorias::get();
    }
}
