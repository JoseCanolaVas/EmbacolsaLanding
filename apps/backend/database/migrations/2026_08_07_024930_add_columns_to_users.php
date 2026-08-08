<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono');
            $table->string('es_super_admin')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nombre');
            $table->dropColumn('apellido');
            $table->dropColumn('telefono');
            $table->dropColumn('es_super_admin');
            $table->string('name');
        });
    }
};
