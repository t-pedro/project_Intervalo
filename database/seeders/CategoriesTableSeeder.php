<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear o actualizar categorías
        $categories = [
            ['title' => 'INNOVACIÓN', 'description' => 'Categoría que se enfoca en la capacidad de Innovación.'],
            ['title' => 'LOGRO', 'description' => 'Categoría que se enfoca en los Logros.'],
            ['title' => 'COLABORACIÓN', 'description' => 'Categoría que se enfoca en la capacidad de Colaboración.'],
            ['title' => 'LIDERAZGO', 'description' => 'Categoría que se enfoca en la capacidad de Liderazgo.']
        ];
        
        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['title' => $category['title']], // Condición para buscar el registro existente
                ['title' => $category['title'],
                'description' => $category['description']]  // Los valores a actualizar o crear
            );
        }
        
    }
}
