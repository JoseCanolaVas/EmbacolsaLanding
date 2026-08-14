<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_configs', function (Blueprint $table) {
            $table->string('etiqueta_catalogo')->nullable()->after('subtitulo_productos');
            $table->string('titulo_catalogo')->nullable()->after('etiqueta_catalogo');
            $table->text('descripcion_catalogo')->nullable()->after('titulo_catalogo');
        });

        DB::table('site_configs')
            ->where('clave', 'principal')
            ->update([
                'etiqueta_catalogo' => 'CATÁLOGO COMPLETO',
                'titulo_catalogo' => 'Productos disponibles para cotizar.',
                'descripcion_catalogo' => 'Explora el portafolio publicado desde parametrización y filtra por nombre, categoría o marca.',
            ]);
    }

    public function down(): void
    {
        Schema::table('site_configs', function (Blueprint $table) {
            $table->dropColumn([
                'etiqueta_catalogo',
                'titulo_catalogo',
                'descripcion_catalogo',
            ]);
        });
    }
};
