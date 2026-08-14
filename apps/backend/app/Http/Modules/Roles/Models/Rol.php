<?php

namespace App\Http\Modules\Roles\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'permisos',
        'estado',
    ];

    protected $casts = [
        'permisos' => 'array',
        'estado' => 'boolean',
    ];
}
