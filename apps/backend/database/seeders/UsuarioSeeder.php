<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->crearSuperAdmin(
            'jose.vasquez@softnova.com',
            'jose',
            'vasquez',
            '3188158107',
            env('SEEDER_JOSE_PASSWORD')
        );

        $this->crearSuperAdmin(
            'juliana@embacolsa.com',
            'juliana',
            'embacolsa',
            '3218720375',
            env('SEEDER_JULIANA_PASSWORD')
        );
    }

    private function crearSuperAdmin(
        string $email,
        string $nombre,
        string $apellido,
        string $telefono,
        ?string $password
    ): void {
        $usuario = User::where('email', $email)->first();
        $datos = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono,
            'es_super_admin' => true,
            'rol' => 'super_admin',
            'permisos' => [],
        ];

        if ($password || ! $usuario) {
            $datos['password'] = Hash::make($password ?: Str::random(40));
        }

        User::updateOrCreate(
            ['email' => $email],
            $datos
        );
    }
}
