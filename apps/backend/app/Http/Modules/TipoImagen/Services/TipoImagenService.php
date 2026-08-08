<?php

namespace App\Http\Modules\TipoImagen\Services;

use Exception;

use App\Http\Modules\TipoImagen\Models\TipoImagen;
use App\Http\Modules\TipoImagen\Repositories\TipoImagenRepository;

class TipoImagenService
{
    public function __construct(
        protected TipoImagenRepository $tipoImagen
    ) {}

    public function crearTipoImagen(array $data)
    {
        return TipoImagen::create($data);
    }

    /**
     * Actualiza un tipo de imagen existente.
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     * @author jose vasquez
     */
    public function actualizarTipoImagen(int $id, array $data)
    {
        $tipoImagen = $this->tipoImagen->obtenerTipoImagenPorId($id);

        if (!$tipoImagen) {
            throw new Exception('Tipo de imagen no encontrado', 422);
        }

        return $tipoImagen->update($data);
    }
}
