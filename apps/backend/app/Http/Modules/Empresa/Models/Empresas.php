<?php

namespace App\Http\Modules\Empresa\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'nit',
        'direccion',
        'telefono',
        'email',
    ];
    
}
