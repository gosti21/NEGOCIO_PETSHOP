<?php

namespace App\Traits\Admin;

use App\Models\Variant;

trait generateCombinations
{
    public function generarVariantes()
    {
        $features = $this->product->options->pluck('pivot.features');
        $combinaciones = $this->generarCombinaciones($features);
        $this->product->variants()->delete();
        foreach ($combinaciones as $combinacion) {

            $variant = Variant::create([
                'product_id' => $this->product->id
            ]);

            $variant->features()->attach($combinacion);
        }
    }

    function generarCombinaciones($arrays, $indice = 0, $combinacion = [])
    {

        if ($indice == count($arrays)) {

            return [$combinacion];
        }

        $resultado = [];

        foreach ($arrays[$indice] as $item) {

            $combinacionesTemporal = $combinacion;
            $combinacionesTemporal[] = $item['id'];

            $resultado = array_merge($resultado, $this->generarCombinaciones($arrays, $indice + 1, $combinacionesTemporal));
        }

        return  $resultado;
    }
}
