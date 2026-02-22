<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Rol::create([
            'nombre' => 'Usuario',
            'descripcion' => 'Rol básico de usuario',
        ]);

        Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Rol con privilegios',
        ]);

        Rol::create([
            'nombre' => 'Mia',
            'descripcion' => 'Responsable de Medios Informáticos y Audiovisuales',
        ]);

        Usuario::create([
            'nombre' => 'Admin',
            'apellidos' => 'Demo',
            'telefono' => '123456789',
            'email' => 'admin@demo.com',
            'password' => bcrypt('admin123'),
            'idRol' => 2, // Asignar el rol de Administrador
        ]);

        Usuario::create([
            'nombre' => 'Usuario',
            'apellidos' => 'Demo',
            'telefono' => '234567891',
            'email' => 'usuario@demo.com',
            'password' => bcrypt('usuario123'),
            'idRol' => 1, // Asignar el rol de Usuario
        ]);

        Usuario::create([
            'nombre' => 'Mia',
            'apellidos' => 'Demo',
            'telefono' => '345678912',
            'email' => 'mia@demo.com',
            'password' => bcrypt('mia123'),
            'idRol' => 3, // Asignar el rol de Mia
        ]);
    }
}
