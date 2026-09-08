<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedInteger('stock')->default(0)->after('precio');
            $table->foreignId('bodega_id')
                ->nullable()
                ->after('marca_id')
                ->constrained('bodegas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bodega_id');
            $table->dropColumn('stock');
        });
    }
};
