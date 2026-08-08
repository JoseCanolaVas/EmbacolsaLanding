<?php

namespace App\Http\Modules\TipoImagen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Modules\Imagenes\Models\Imagenes;

class TipoImagen extends Model
{
    use HasFactory;

    protected $table = 'tipo_imagenes';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function imagenes()
    {
        return $this->hasMany(Imagenes::class, 'tipo_imagen_id');
    }
}
