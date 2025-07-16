<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Colocamos 1 en type referenciando que los valores seran de tipo texto pero si es 2 de tipo color */
        $options = [
            [
                'name' => 'Material',
                'type' => 1,
                'features' => [
                    [
                        'value' => 'metal',
                        'description' => 'metal',
                    ],
                    [
                        'value' => 'plastic',
                        'description' => 'plastic',
                    ],
                    [
                        'value' => 'glass',
                        'description' => 'glass',
                    ],
                    [
                        'value' => 'ceramic',
                        'description' => 'ceramic',
                    ],
                    [
                        'value' => 'marmol',
                        'description' => 'marmol',
                    ],
                ]
            ],
            [
                'name' => 'Color',
                'type' => 2,
                'features' => [
                    [
                        'value' => '#000000',
                        'description' => 'black'
                    ],
                    [
                        'value' => '#ffffff',
                        'description' => 'white'
                    ],
                    [
                        'value' => '#ff0000',
                        'description' => 'red'
                    ],
                    [
                        'value' => '#00ff00',
                        'description' => 'green'
                    ],
                    [
                        'value' => '#0000ff',
                        'description' => 'blue'
                    ],
                    [
                        'value' => '#ffff00',
                        'description' => 'yellow'
                    ],
                ],
            ],
            [
                'name' => 'Medidas',
                'type' => 1,
                'features' => [
                    [
                        'value' => 'U',
                        'description' => 'unique',
                    ],
                    [
                        'value' => 'S',
                        'description' => 'small',
                    ],
                    [
                        'value' => 'M',
                        'description' => 'medium',
                    ],
                    [
                        'value' => 'B',
                        'description' => 'big',
                    ],
                ]
            ],
        ];

        foreach($options as $option){
            $optionModel = Option::create([
                'name' => $option['name'],
                'type' => $option['type'],
            ]);

            foreach($option['features'] as $feature){
                $optionModel->features()->create([
                    'option_id' => $optionModel->id,
                    'value' => $feature['value'],
                    'description' => $feature['description'],
                ]);
            }
        }
    }
}
