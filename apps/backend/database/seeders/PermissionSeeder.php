<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\PermissionCatalog;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (PermissionCatalog::permisos() as $item) {
            $permiso = Permission::firstOrCreate([
                'name' => $item['value'],
                'guard_name' => PermissionCatalog::GUARD,
            ]);

            $permiso->forceFill([
                'label' => $item['label'],
                'grupo' => $item['grupo'],
                'descripcion' => $item['label'],
                'estado' => true,
            ])->save();
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => PermissionCatalog::GUARD,
        ]);
        $superAdmin->forceFill([
            'display_name' => 'Super administrador',
            'descripcion' => 'Acceso total al panel y a la configuración.',
            'estado' => true,
        ])->save();
        $superAdmin->syncPermissions(PermissionCatalog::nombres());

        $administrador = Role::firstOrCreate([
            'name' => 'administrador',
            'guard_name' => PermissionCatalog::GUARD,
        ]);
        $administrador->forceFill([
            'display_name' => 'Administrador',
            'descripcion' => 'Administra catálogo, usuarios, roles y sitio público.',
            'estado' => true,
        ])->save();
        $administrador->syncPermissions(PermissionCatalog::nombres());

        $editor = Role::firstOrCreate([
            'name' => 'editor_catalogo',
            'guard_name' => PermissionCatalog::GUARD,
        ]);
        $editor->forceFill([
            'display_name' => 'Editor de catálogo',
            'descripcion' => 'Gestiona productos, categorías, marcas, imágenes y textos del sitio.',
            'estado' => true,
        ])->save();
        $editor->syncPermissions([
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
            'bodegas.ver',
            'bodegas.crear',
            'bodegas.editar',
            'ventas.ver',
            'ventas.crear',
            'imagenes.ver',
            'imagenes.crear',
            'imagenes.editar',
            'imagenes.eliminar',
            'administrar-sitio',
        ]);

        User::where('es_super_admin', true)->get()->each(function (User $user) {
            $user->assignRole('super_admin');
        });

        User::where('es_super_admin', false)->whereNotNull('rol')->get()->each(function (User $user) {
            if (Role::where('name', $user->rol)->where('guard_name', PermissionCatalog::GUARD)->exists()) {
                $user->syncRoles([$user->rol]);
            }

            if (is_array($user->permisos) && count($user->permisos)) {
                $validos = Permission::whereIn('name', $user->permisos)->pluck('name')->all();
                $user->syncPermissions($validos);
            }
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
