<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'es_super_admin',
        'rol',
        'permisos',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'es_super_admin' => 'boolean',
        'permisos' => 'array',
        'email_verified_at' => 'datetime',
    ];

    public function permisosDisponibles(): array
    {
        if ($this->es_super_admin) {
            return [
                'panel.ver',
                'productos.ver',
                'productos.crear',
                'productos.editar',
                'categorias.ver',
                'categorias.crear',
                'categorias.editar',
                'marcas.ver',
                'marcas.crear',
                'marcas.editar',
                'imagenes.ver',
                'imagenes.crear',
                'imagenes.editar',
                'imagenes.eliminar',
                'usuarios.ver',
                'usuarios.crear',
                'usuarios.editar',
                'roles.ver',
                'roles.crear',
                'roles.editar',
                'administrar-sitio',
            ];
        }

        return $this->permisos ?? [];
    }
}
