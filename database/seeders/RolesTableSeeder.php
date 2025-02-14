<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;


class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => 'Administrador', 'description' => 'Administrador total del sistema.'],
            ['name' => 'Manager', 'description' => 'Administrador total dentro de una empresa'],
            ['name' => 'Empleado', 'description' => 'Usuario básico, perteneciente a una empresa'],
        ];
        
        foreach ($statuses as $status) {
            Rol::updateOrCreate(
                ['name' => $status['name']], // Condición para buscar el registro existente
                ['description' => $status['description']] // Valores a actualizar o crear
            );
        }
        
    }
}
