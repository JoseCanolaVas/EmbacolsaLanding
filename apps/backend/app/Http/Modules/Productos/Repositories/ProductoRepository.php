<?php

namespace App\Http\Modules\Productos\Repositories;

use App\Http\Modules\Productos\Models\Productos;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductoRepository
{

    /**
     * Listar productos con paginación o sin paginación según los parámetros proporcionados.
     * @param array $data Datos de entrada que incluyen la paginación.
     * @return LengthAwarePaginator|Collection 
     * @author jose vasquez
     */
    public function listarProductos(array $data): LengthAwarePaginator|Collection
    {
        $paginacion = $data['paginacion'];

        $productos = Productos::select('id', 'nombre', 'descripcion', 'ruta_imagen', 'estado', 'unidad_medida', 'precio', 'categoria_id')
            ->with('categoria')
            ->orderBy('id', 'desc');

        return !empty($paginacion)
            ? $productos->paginate($paginacion['cantidadRegistros'], ['*'], 'page', $paginacion['pagina'])
            : $productos->get();
    }
}
