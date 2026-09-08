<?php

namespace App\Support;

class PermissionCatalog
{
    public const GUARD = 'api';

    public static function permisos(): array
    {
        return [
            ['value' => 'panel.ver', 'label' => 'Ver panel', 'grupo' => 'Panel'],
            ['value' => 'productos.ver', 'label' => 'Ver productos', 'grupo' => 'Productos'],
            ['value' => 'productos.crear', 'label' => 'Crear productos', 'grupo' => 'Productos'],
            ['value' => 'productos.editar', 'label' => 'Editar productos', 'grupo' => 'Productos'],
            ['value' => 'categorias.ver', 'label' => 'Ver categorías', 'grupo' => 'Categorías'],
            ['value' => 'categorias.crear', 'label' => 'Crear categorías', 'grupo' => 'Categorías'],
            ['value' => 'categorias.editar', 'label' => 'Editar categorías', 'grupo' => 'Categorías'],
            ['value' => 'marcas.ver', 'label' => 'Ver marcas', 'grupo' => 'Marcas'],
            ['value' => 'marcas.crear', 'label' => 'Crear marcas', 'grupo' => 'Marcas'],
            ['value' => 'marcas.editar', 'label' => 'Editar marcas', 'grupo' => 'Marcas'],
            ['value' => 'bodegas.ver', 'label' => 'Ver bodegas', 'grupo' => 'Bodegas'],
            ['value' => 'bodegas.crear', 'label' => 'Crear bodegas', 'grupo' => 'Bodegas'],
            ['value' => 'bodegas.editar', 'label' => 'Editar bodegas', 'grupo' => 'Bodegas'],
            ['value' => 'ventas.ver', 'label' => 'Ver ventas', 'grupo' => 'Ventas'],
            ['value' => 'ventas.crear', 'label' => 'Registrar ventas POS', 'grupo' => 'Ventas'],
            ['value' => 'imagenes.ver', 'label' => 'Ver imágenes', 'grupo' => 'Imágenes'],
            ['value' => 'imagenes.crear', 'label' => 'Crear imágenes', 'grupo' => 'Imágenes'],
            ['value' => 'imagenes.editar', 'label' => 'Editar imágenes', 'grupo' => 'Imágenes'],
            ['value' => 'imagenes.eliminar', 'label' => 'Eliminar imágenes', 'grupo' => 'Imágenes'],
            ['value' => 'usuarios.ver', 'label' => 'Ver usuarios', 'grupo' => 'Usuarios'],
            ['value' => 'usuarios.crear', 'label' => 'Crear usuarios', 'grupo' => 'Usuarios'],
            ['value' => 'usuarios.editar', 'label' => 'Editar usuarios', 'grupo' => 'Usuarios'],
            ['value' => 'roles.ver', 'label' => 'Ver roles y permisos', 'grupo' => 'Seguridad'],
            ['value' => 'roles.crear', 'label' => 'Crear roles', 'grupo' => 'Seguridad'],
            ['value' => 'roles.editar', 'label' => 'Editar roles', 'grupo' => 'Seguridad'],
            ['value' => 'permisos.ver', 'label' => 'Ver permisos', 'grupo' => 'Seguridad'],
            ['value' => 'permisos.crear', 'label' => 'Crear permisos', 'grupo' => 'Seguridad'],
            ['value' => 'permisos.editar', 'label' => 'Editar permisos', 'grupo' => 'Seguridad'],
            ['value' => 'administrar-sitio', 'label' => 'Administrar sitio público', 'grupo' => 'Sitio público'],
        ];
    }

    public static function nombres(): array
    {
        return collect(self::permisos())->pluck('value')->all();
    }

    public static function permiso(string $name): array
    {
        return collect(self::permisos())->firstWhere('value', $name) ?: [
            'value' => $name,
            'label' => ucfirst(str_replace(['.', '-'], ' ', $name)),
            'grupo' => 'Otros',
        ];
    }
}
