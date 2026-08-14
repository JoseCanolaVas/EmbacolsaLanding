<?php

namespace App\Http\Modules\SitioPublico\Models;

use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    protected $table = 'site_configs';

    protected $fillable = [
        'clave',
        'nombre_sitio',
        'eyebrow',
        'titulo',
        'descripcion',
        'titulo_productos',
        'subtitulo_productos',
        'etiqueta_catalogo',
        'titulo_catalogo',
        'descripcion_catalogo',
        'titulo_beneficios',
        'descripcion_beneficios',
        'titulo_contacto',
        'descripcion_contacto',
        'telefono_whatsapp',
        'correo_contacto',
        'beneficios',
        'colores',
        'estado',
    ];

    protected $casts = [
        'beneficios' => 'array',
        'colores' => 'array',
        'estado' => 'boolean',
    ];
}
