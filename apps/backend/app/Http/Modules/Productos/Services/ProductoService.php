<?php

namespace App\Http\Modules\Productos\Services;

use App\Http\Modules\Productos\Models\Productos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductoService
{
    public function __construct() {}

    public function crearProducto(array $data)
    {
        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'unidad_medida' => ['required', 'string', 'max:50'],
            'estado' => ['required', 'boolean'],
            'precio' => ['nullable', 'numeric', 'min:0'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'marca_id' => ['nullable', 'exists:marcas,id'],
            'imagen' => ['required', 'image', 'max:5120'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $datosValidados = $validator->validated();
        $imagen = $datosValidados['imagen'];
        unset($datosValidados['imagen']);

        if (! $imagen instanceof UploadedFile) {
            throw ValidationException::withMessages([
                'imagen' => ['La imagen enviada no es un archivo valido.'],
            ]);
        }

        $carpeta = 'productos/' . now()->format('Y/m');
        $nombreArchivo = Str::slug($datosValidados['nombre'])
            . '-' . now()->format('YmdHis')
            . '-' . Str::random(8)
            . '.' . $imagen->getClientOriginalExtension();

        $rutaImagen = $imagen->storeAs($carpeta, $nombreArchivo, 'public');

        $datosValidados['descripcion'] = $datosValidados['descripcion'] ?? 'Sin descripcion por ahora';
        $datosValidados['ruta_imagen'] = Storage::url($rutaImagen);

        return Productos::create($datosValidados);
    }

    public function actualizarProducto(Productos $producto, array $data)
    {
        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'unidad_medida' => ['required', 'string', 'max:50'],
            'estado' => ['required', 'boolean'],
            'precio' => ['nullable', 'numeric', 'min:0'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'marca_id' => ['nullable', 'exists:marcas,id'],
            'imagen' => ['nullable', 'image', 'max:5120'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $datosValidados = $validator->validated();
        $imagen = $datosValidados['imagen'] ?? null;
        unset($datosValidados['imagen']);

        if ($imagen) {
            if (! $imagen instanceof UploadedFile) {
                throw ValidationException::withMessages([
                    'imagen' => ['La imagen enviada no es un archivo valido.'],
                ]);
            }

            $this->eliminarArchivoFisico($producto->ruta_imagen);

            $carpeta = 'productos/' . now()->format('Y/m');
            $nombreArchivo = Str::slug($datosValidados['nombre'])
                . '-' . now()->format('YmdHis')
                . '-' . Str::random(8)
                . '.' . $imagen->getClientOriginalExtension();

            $rutaImagen = $imagen->storeAs($carpeta, $nombreArchivo, 'public');
            $datosValidados['ruta_imagen'] = Storage::url($rutaImagen);
        }

        $datosValidados['descripcion'] = $datosValidados['descripcion'] ?? 'Sin descripcion por ahora';

        $producto->update($datosValidados);

        return $producto->fresh(['categoria', 'marca']);
    }

    private function eliminarArchivoFisico(?string $ruta): void
    {
        if (! $ruta) {
            return;
        }

        $rutaRelativa = preg_replace('/^\/?storage\//', '', $ruta);

        if ($rutaRelativa && Storage::disk('public')->exists($rutaRelativa)) {
            Storage::disk('public')->delete($rutaRelativa);
        }
    }
}
