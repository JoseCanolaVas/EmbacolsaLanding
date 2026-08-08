<?php

namespace App\Http\Modules\Imagenes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Modules\TipoImagen\Models\TipoImagen;

class Imagenes extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ruta',
        'tipo_imagen_id',
    ];

    public function tipoImagen()
    {
        return $this->belongsTo(TipoImagen::class, 'tipo_imagen_id');
    }
}
