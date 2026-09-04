<?php

namespace Database\Seeders;

use App\Http\Modules\Bodegas\Models\Bodegas;
use Illuminate\Database\Seeder;

class BodegaSeeder extends Seeder
{
    public function run(): void
    {
        Bodegas::firstOrCreate(
            ['codigo' => 'PRINCIPAL'],
            [
                'nombre' => 'Bodega principal',
                'ubicacion' => 'Principal',
                'descripcion' => 'Bodega por defecto para controlar el stock inicial de productos.',
                'estado' => true,
            ]
        );
    }
}
