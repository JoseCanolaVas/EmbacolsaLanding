<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roles') && ! Schema::hasTable('permissions') && ! Schema::hasTable('roles_legacy')) {
            Schema::rename('roles', 'roles_legacy');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('roles_legacy') && ! Schema::hasTable('roles')) {
            Schema::rename('roles_legacy', 'roles');
        }
    }
};
