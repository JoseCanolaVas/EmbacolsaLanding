<?php

namespace App\Http\Modules\Productos\Models;

use App\Http\Modules\Categorias\Models\Categorias;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'ruta_imagen',
        'estado',
        'unidad_medida',
        'precio',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }
}
