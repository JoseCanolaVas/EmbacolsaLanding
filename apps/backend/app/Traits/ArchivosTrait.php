<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ArchivosTrait
{
    /**
     * Subir un archivo.
     */
    public function subirArchivo(UploadedFile $archivo, string $directorio = 'archivos', string $disk = 'public'): string
    {
        return $archivo->store($directorio, $disk);
    }

    /**
     * Eliminar un archivo.
     */
    public function eliminarArchivo(?string $ruta, string $disk = 'public'): bool
    {
        if (!$ruta) {
            return false;
        }

        if (!Storage::disk($disk)->exists($ruta)) {
            return false;
        }

        return Storage::disk($disk)->delete($ruta);
    }

    /**
     * Obtener la URL pública de un archivo.
     */
    public function urlArchivo(?string $ruta, string $disk = 'public'): ?string
    {
        if (!$ruta) {
            return null;
        }

        return Storage::disk($disk)->url($ruta);
    }

    /**
     * Verificar si un archivo existe.
     */
    public function existeArchivo(?string $ruta, string $disk = 'public'): bool
    {
        if (!$ruta) {
            return false;
        }
        return Storage::disk($disk)->exists($ruta);
    }
}
