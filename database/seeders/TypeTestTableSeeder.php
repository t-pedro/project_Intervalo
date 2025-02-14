<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\TestType;
use Illuminate\Database\Seeder;


class TypeTestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['description' => 'Competencia'],
            ['description' => 'Diagnostico']
        ];
        
        foreach ($types as $type) {
            TestType::updateOrCreate(
                ['description' => $type['description']] // Valores a actualizar o crear
            );
        }
        
    }
}
