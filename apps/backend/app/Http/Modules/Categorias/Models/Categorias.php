<?php

namespace App\Http\Modules\Categorias\Models;

use App\Http\Modules\Productos\Models\Productos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function productos()
    {
        return $this->hasMany(Productos::class, 'categoria_id');
    }
}
