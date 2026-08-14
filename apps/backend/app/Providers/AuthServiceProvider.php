<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Passport::loadKeysFrom(storage_path('oauth-keys'));

        Gate::before(function ($user) {
            return $user->es_super_admin ? true : null;
        });

        foreach ($this->permisosSistema() as $permiso) {
            Gate::define($permiso, function ($user) use ($permiso) {
                return in_array($permiso, $user->permisos ?? [], true);
            });
        }
    }

    private function permisosSistema(): array
    {
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
}
