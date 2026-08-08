<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'jose.vasquez@softnova.com',
            ],
            [
                'nombre' => 'jose',
                'apellido' => 'vasquez',
                'telefono' => '3188158107',
                'es_super_admin' => true,
                'password' => Hash::make('X27carjo'),
            ]
        );
    }
}
