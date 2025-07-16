<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Family;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $families = [
            1 => ['Decohogar', 'Organización'],
            2 => ['Comedor', 'Oficina', 'Sala']
        ];

        foreach ($families as $familyId => $categories) {
            foreach ($categories as $category)
            Category::create([
                'name' => $category,
                'family_id' => $familyId
            ]);
        }
    }
}
