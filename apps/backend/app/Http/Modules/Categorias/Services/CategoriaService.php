<?php

namespace App\Http\Modules\Categorias\Services;

use App\Http\Modules\Categorias\Models\Categorias;

class CategoriaService
{
    public function __construct() {}

    /**
     * Crear una nueva categoría.
     *
     * @param array $data
     * @return Categorias
     * @author jose vasquez
     */
    public function crearCategoria(array $data)
    {
        return Categorias::create($data);
    }

    public function actualizarCategoria(array $data, $id)
    {
        $categoria = Categorias::find($id);
        if ($categoria) {
            $categoria->update($data);
        }
        return $categoria;
    }
}
