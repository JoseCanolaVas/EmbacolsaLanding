<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_configs', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->string('nombre_sitio')->default('Embacolsa');
            $table->string('eyebrow')->nullable();
            $table->string('titulo')->default('Embacolsa');
            $table->text('descripcion')->nullable();
            $table->string('titulo_productos')->nullable();
            $table->string('subtitulo_productos')->nullable();
            $table->string('titulo_beneficios')->nullable();
            $table->text('descripcion_beneficios')->nullable();
            $table->string('titulo_contacto')->nullable();
            $table->text('descripcion_contacto')->nullable();
            $table->string('telefono_whatsapp')->nullable();
            $table->string('correo_contacto')->nullable();
            $table->json('beneficios')->nullable();
            $table->json('colores')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_configs');
    }
};
