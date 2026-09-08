<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
            $table->text('descripcion')->nullable()->after('display_name');
            $table->boolean('estado')->default(true)->after('descripcion');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('label')->nullable()->after('name');
            $table->string('grupo')->nullable()->after('label');
            $table->text('descripcion')->nullable()->after('grupo');
            $table->boolean('estado')->default(true)->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'descripcion', 'estado']);
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['label', 'grupo', 'descripcion', 'estado']);
        });
    }
};
