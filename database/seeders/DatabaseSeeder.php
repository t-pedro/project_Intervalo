<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Afirmation;
use App\Models\Competencia;
use App\Models\Category;
use App\Models\TestStatus;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call([
            CategoriesTableSeeder::class,
            DiagnosticoStatusTableSeeder::class,
            RolesTableSeeder::class,
            TestStatusTableSeeder::class,
            //...
        ]);

        // Creación del usuario Principal.
        User::updateOrCreate(
            ['email' => 'g@gmail.com'], // Condición para buscar el registro existente
            [
                'name' => 'German Middi',
                'password' => bcrypt('Inicio123')
            ]
        );
        
    }
}
