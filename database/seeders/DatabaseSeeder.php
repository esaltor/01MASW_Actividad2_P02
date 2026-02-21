<?php

namespace Database\Seeders;

use App\Models\Rol;
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
    }
}
