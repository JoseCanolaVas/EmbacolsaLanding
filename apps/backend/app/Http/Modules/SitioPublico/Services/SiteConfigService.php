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
            'nombre_sitio' => 'Embacolsa',
            'eyebrow' => 'EMPAQUES, EMBALAJES Y SUMINISTROS',
            'titulo' => 'Embacolsa',
            'descripcion' => 'Soluciones listas para empacar, proteger y despachar tu operación con mejor imagen, menos vueltas y respuesta comercial rápida.',
            'titulo_productos' => 'Una muestra por cada categoría activa',
            'subtitulo_productos' => 'Catálogo parametrizado',
            'etiqueta_catalogo' => 'CATÁLOGO COMPLETO',
            'titulo_catalogo' => 'Productos disponibles para cotizar.',
            'descripcion_catalogo' => 'Explora el portafolio publicado desde parametrización y filtra por nombre, categoría o marca.',
            'titulo_beneficios' => 'Acompañamiento para comprar mejor, no solo comprar más.',
            'descripcion_beneficios' => 'Te ayudamos a escoger materiales según carga, rotación, presupuesto y presentación final.',
            'titulo_contacto' => 'Cuéntanos qué necesitas empacar.',
            'descripcion_contacto' => 'Armamos una cotización clara para tu empresa.',
            'telefono_whatsapp' => '573218720375',
            'correo_contacto' => 'comercial@embacolsa.com.co',
            'beneficios' => [
                ['icon' => 'mdi-truck-fast-outline', 'title' => 'Despacho confiable', 'text' => 'Coordinación para que tu operación no se frene.'],
                ['icon' => 'mdi-account-tie-outline', 'title' => 'Asesoría real', 'text' => 'Recomendaciones según el producto y el volumen.'],
                ['icon' => 'mdi-layers-triple-outline', 'title' => 'Portafolio completo', 'text' => 'Compra lo esencial desde un solo proveedor.'],
                ['icon' => 'mdi-message-reply-text-outline', 'title' => 'Atención directa', 'text' => 'Cotizaciones claras y respuesta sin tanta vuelta.'],
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
