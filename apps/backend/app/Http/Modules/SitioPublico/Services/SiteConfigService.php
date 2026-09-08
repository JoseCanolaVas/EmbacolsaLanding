<?php

namespace App\Http\Modules\SitioPublico\Services;

use App\Http\Modules\SitioPublico\Models\SiteConfig;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SiteConfigService
{
    public const CLAVE_PRINCIPAL = 'principal';

    public function obtenerPrincipal(): SiteConfig
    {
        return SiteConfig::firstOrCreate(
            ['clave' => self::CLAVE_PRINCIPAL],
            $this->valoresPorDefecto()
        );
    }

    public function actualizarPrincipal(array $data): SiteConfig
    {
        $validator = Validator::make($data, [
            'nombre_sitio' => ['required', 'string', 'max:120'],
            'eyebrow' => ['nullable', 'string', 'max:160'],
            'titulo' => ['required', 'string', 'max:140'],
            'descripcion' => ['nullable', 'string', 'max:800'],
            'titulo_productos' => ['nullable', 'string', 'max:180'],
            'subtitulo_productos' => ['nullable', 'string', 'max:260'],
            'etiqueta_catalogo' => ['nullable', 'string', 'max:160'],
            'titulo_catalogo' => ['nullable', 'string', 'max:180'],
            'descripcion_catalogo' => ['nullable', 'string', 'max:700'],
            'titulo_beneficios' => ['nullable', 'string', 'max:180'],
            'descripcion_beneficios' => ['nullable', 'string', 'max:700'],
            'titulo_contacto' => ['nullable', 'string', 'max:180'],
            'descripcion_contacto' => ['nullable', 'string', 'max:500'],
            'telefono_whatsapp' => ['nullable', 'string', 'max:30'],
            'correo_contacto' => ['nullable', 'email', 'max:180'],
            'beneficios' => ['nullable', 'array'],
            'beneficios.*.icon' => ['nullable', 'string', 'max:80'],
            'beneficios.*.title' => ['nullable', 'string', 'max:120'],
            'beneficios.*.text' => ['nullable', 'string', 'max:260'],
            'colores' => ['nullable', 'array'],
            'colores.primario' => ['nullable', 'string', 'max:20'],
            'colores.secundario' => ['nullable', 'string', 'max:20'],
            'colores.acento' => ['nullable', 'string', 'max:20'],
            'colores.fondo' => ['nullable', 'string', 'max:20'],
            'estado' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $configuracion = $this->obtenerPrincipal();
        $datos = $validator->validated();
        $datos['clave'] = self::CLAVE_PRINCIPAL;
        $datos['beneficios'] = array_values(array_filter($datos['beneficios'] ?? [], function ($beneficio) {
            return ! empty($beneficio['title']) || ! empty($beneficio['text']);
        }));
        $datos['colores'] = array_merge(
            $this->valoresPorDefecto()['colores'],
            $datos['colores'] ?? []
        );
        $datos['estado'] = $datos['estado'] ?? true;

        $configuracion->update($datos);

        return $configuracion->fresh();
    }

    public function valoresPorDefecto(): array
    {
        return [
            'clave' => self::CLAVE_PRINCIPAL,
            'nombre_sitio' => 'NovaCell',
            'eyebrow' => 'CELULARES, ACCESORIOS Y TECNOLOGÍA',
            'titulo' => 'NovaCell',
            'descripcion' => 'Celulares y accesorios listos para cuidar, cargar y potenciar tu equipo: forros, cables, audífonos, vidrios templados, soportes y cargadores en un solo lugar.',
            'titulo_productos' => 'Una muestra por cada categoría activa',
            'subtitulo_productos' => 'Accesorios destacados',
            'etiqueta_catalogo' => 'CATÁLOGO NOVACELL',
            'titulo_catalogo' => 'Accesorios y tecnología disponibles.',
            'descripcion_catalogo' => 'Explora el portafolio publicado desde parametrización y filtra por nombre, categoría o marca: forros, cargadores, cables, audífonos, soportes y más.',
            'titulo_beneficios' => 'Compra tecnología con asesoría real y sin vueltas.',
            'descripcion_beneficios' => 'Te ayudamos a elegir accesorios compatibles, resistentes y útiles para tu celular, tu carro, tu casa o tu negocio.',
            'titulo_contacto' => 'Cuéntanos qué accesorio necesitas.',
            'descripcion_contacto' => 'Armamos una cotización rápida con opciones compatibles para tu equipo.',
            'telefono_whatsapp' => '573188158107',
            'correo_contacto' => 'comercial@embacolsa.com.co',
            'beneficios' => [
                ['icon' => 'mdi-cellphone-check', 'title' => 'Compatibilidad clara', 'text' => 'Te orientamos para elegir accesorios que sí funcionan con tu equipo.'],
                ['icon' => 'mdi-shield-phone-outline', 'title' => 'Protección completa', 'text' => 'Forros, vidrios templados y soportes para cuidar tu celular todos los días.'],
                ['icon' => 'mdi-battery-charging-high', 'title' => 'Carga y conexión', 'text' => 'Cargadores, cables y adaptadores para casa, oficina y carro.'],
                ['icon' => 'mdi-headphones', 'title' => 'Audio y movilidad', 'text' => 'Audífonos y accesorios prácticos para trabajar, viajar y disfrutar.'],
            ],
            'colores' => [
                'primario' => '#0f2c61',
                'secundario' => '#0d7880',
                'acento' => '#1e88e5',
                'fondo' => '#f4f8fb',
            ],
            'estado' => true,
        ];
    }
}
