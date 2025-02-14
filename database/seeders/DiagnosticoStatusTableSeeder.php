<?php

namespace Database\Seeders;

use App\Models\DiagnosticoStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DiagnosticoStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            ['description' => 'BORRADOR'],
            ['description' => 'HABILITADO'],
            ['description' => 'CANCELADO'],
            ['description' => 'FINALIZADO'],
        ];
        
        foreach ($status as $statusItem) {
            DiagnosticoStatus::updateOrCreate(
                ['description' => $statusItem['description']], // Condición para buscar el registro existente
                $statusItem // Los valores a actualizar o crear
            );
        }
        
    }
}
