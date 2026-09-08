<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Support\PermissionCatalog;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected string $guard_name = PermissionCatalog::GUARD;

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
            return PermissionCatalog::nombres();
        }

        $permisosJson = is_array($this->permisos) ? $this->permisos : [];
        $permisosSpatie = $this->getAllPermissions()->pluck('name')->all();

        return array_values(array_unique(array_merge($permisosJson, $permisosSpatie)));
    }
}
