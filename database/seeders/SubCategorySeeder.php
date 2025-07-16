<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            1 => ['Iluminación', 'Otros'],
            2 => ['Repisas', 'Otros'],
            3 => ['Alacenas', 'Mesas', 'Sillas', 'Otros'],
            4 => ['Cuadros', 'Escritorios', 'Sillas', 'Estatuas', 'Otros'],
            5 => ['Accesorios', 'Mesas', 'Sillas', 'Sofás', 'Otros']
        ];

        foreach ($categories as $categoryId => $subCategories){
            foreach ($subCategories as $subCategory){
                SubCategory::create([
                    'name' => $subCategory,
                    'category_id' => $categoryId
                ]);
            }
        }
    }
}
