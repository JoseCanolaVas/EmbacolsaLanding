<?php

namespace App\Http\Modules\Productos\Services;

use App\Http\Modules\Productos\Models\Productos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductoService
{
    public function __construct() {}

    /**
     * Crear producto
     * @param array $data
     * @return Productos|array
     * @author jose vasquez
     */
    public function crearProducto(array $data)
    {
        $imagen = $data['imagen'];
        unset($data['imagen']);

        if (!$imagen instanceof UploadedFile) {
            return [
                'status' => 422,
                'mensaje' => 'La imagen enviada no es un archivo valido.'
            ];
        }

        $carpeta = 'Productos';
        $nombreArchivo = Str::uuid() . '.' . $imagen->getClientOriginalExtension();

        $rutaImagen = $imagen->storeAs($carpeta, $nombreArchivo, 'public');

        $data['descripcion'] = $data['descripcion'] ?? 'Sin descripcion';
        $data['stock'] = $data['stock'] ?? 0;
        $data['ruta_imagen'] = $this->rutaPublica($rutaImagen);

        return Productos::create($data);
    }

    /**
     * Actualizar producto
     * @param array $data
     * @param Productos $producto
     * @return Productos
     * @throws ValidationException
     */
    public function actualizarProducto(array $data, Productos $producto)
    {
        $imagen = $data['imagen'] ?? null;
        unset($data['imagen']);

        if ($imagen) {
            if (!$imagen instanceof UploadedFile) {
                return [
                    'status' => 422,
                    'mensaje' => 'La imagen enviada no es un archivo valido.'
                ];
            }

            $this->eliminarArchivoFisico($producto->ruta_imagen);

            $carpeta = 'productos/' . now()->format('Y/m');
            $nombreArchivo = Str::slug($data['nombre']) . '-' . now()->format('YmdHis') . '-' . Str::uuid() . '.' . $imagen->getClientOriginalExtension();
            $rutaImagen = $imagen->storeAs($carpeta, $nombreArchivo, 'public');
            $data['ruta_imagen'] = $this->rutaPublica($rutaImagen);
        }

        $data['descripcion'] = $data['descripcion'] ?? 'Sin descripcion por ahora';
        $data['stock'] = $data['stock'] ?? 0;

        $producto->update($data);

        return $producto->fresh(['categoria', 'marca', 'bodega']);
    }

    private function eliminarArchivoFisico(?string $ruta): void
    {
        if (! $ruta) {
            return;
        }

        $rutaPath = parse_url($ruta, PHP_URL_PATH) ?: $ruta;
        $rutaRelativa = preg_replace('/^\/?storage\//', '', $rutaPath);

        if ($rutaRelativa && Storage::disk('public')->exists($rutaRelativa)) {
            Storage::disk('public')->delete($rutaRelativa);
        }
    }

    private function rutaPublica(string $ruta): string
    {
        return '/storage/' . ltrim($ruta, '/');
    }
}
