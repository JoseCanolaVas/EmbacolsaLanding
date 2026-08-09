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
        $paginacion = $data['paginacion'] ?? null;

        $productos = Productos::select('id', 'nombre', 'descripcion', 'ruta_imagen', 'estado', 'unidad_medida', 'precio', 'categoria_id', 'marca_id')
            ->with(['categoria', 'marca'])
            ->orderBy('id', 'desc');

        if (!empty($data['nombre'])) {
            $productos->where(function ($query) use ($data) {
                $query->where('nombre', 'like', '%' . $data['nombre'] . '%')
                    ->orWhere('descripcion', 'like', '%' . $data['nombre'] . '%')
                    ->orWhere('unidad_medida', 'like', '%' . $data['nombre'] . '%');
            });
        }

        if (!empty($data['categoria'])) {
            $productos->where('categoria_id', $data['categoria']);
        }

        if (!empty($data['marca'])) {
            $productos->where('marca_id', $data['marca']);
        }

        if (array_key_exists('estado', $data) && $data['estado'] !== null && $data['estado'] !== '') {
            $productos->where('estado', filter_var($data['estado'], FILTER_VALIDATE_BOOLEAN));
        }

        return !empty($paginacion)
            ? $productos->paginate($paginacion['cantidadRegistros'], ['*'], 'page', $paginacion['pagina'])
            : $productos->get();
    }
}
