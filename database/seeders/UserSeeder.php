<?php

namespace Database\Seeders;

use App\Models\Postulantes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Crear administrador
         $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@hotmail.com',
            'password' => bcrypt('321admin'),
            'role' => 'admin',
        ]);

        // Crear usuario postulante
        $usuario = User::create([
            'name' => 'Jorge',
            'email' => 'jorge@hotmail.com',
            'password' => bcrypt('321admin'),
            'role' => 'postulante',
        ]);
        Postulantes::create([
            'user_id' => $usuario->id,
            'numero_documento' => '123456789',
            'tipo_documento' => 'DNI',
            'nombres' => 'Jorge',
            'apellidos' => 'Perez',
            'fecha_nacimiento' => '1990-01-01',
        ]);
    }
}
