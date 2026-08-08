<?php

namespace App\Http\Modules\Imagenes\Services;

use App\Http\Modules\Imagenes\Models\Imagenes;
use App\Http\Modules\TipoImagen\Models\TipoImagen;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ImagenService
{
    public function crearImagen(array $data)
    {
        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'tipo_imagen_id' => ['required', 'exists:tipo_imagenes,id'],
            'imagen' => ['required', 'image', 'max:5120'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $datosValidados = $validator->validated();
        $imagen = $datosValidados['imagen'];

        if (! $imagen instanceof UploadedFile) {
            throw ValidationException::withMessages([
                'imagen' => ['La imagen enviada no es un archivo valido.'],
            ]);
        }

        $tipoImagen = TipoImagen::find($datosValidados['tipo_imagen_id']);
        $carpeta = 'imagenes/' . Str::slug($tipoImagen->nombre) . '/' . now()->format('Y/m');
        $nombreArchivo = Str::slug($datosValidados['nombre'])
            . '-' . now()->format('YmdHis')
            . '-' . Str::random(8)
            . '.' . $imagen->getClientOriginalExtension();

        $rutaImagen = $imagen->storeAs($carpeta, $nombreArchivo, 'public');

        return Imagenes::create([
            'nombre' => $datosValidados['nombre'],
            'tipo_imagen_id' => $datosValidados['tipo_imagen_id'],
            'ruta' => Storage::url($rutaImagen),
        ]);
    }
}
