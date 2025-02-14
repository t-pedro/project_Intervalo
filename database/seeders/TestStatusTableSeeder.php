<?php

namespace Database\Seeders;

use App\Models\TestStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class TestStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            ['description' => 'ABANDONED'],
            ['description' => 'FINISHED'],
        ];
        
        foreach ($status as $statusItem) {
            TestStatus::updateOrCreate(
                ['description' => $statusItem['description']], // Condición para buscar el registro existente
                $statusItem // Los valores a actualizar o crear
            );
        }
        
    }
}
