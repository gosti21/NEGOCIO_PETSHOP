<?php

namespace App\Rules\Admin;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueVariantFeature implements ValidationRule
{
    protected $productId;
    protected $variants;
    protected static $duplicateRows = [];

    public function __construct($productId, $variants)
    {
        $this->productId = $productId;
        $this->variants = $variants;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = explode('.', $attribute)[1]; // Extraer el índice de la variante en el formulario

        $optionId = $this->variants[$index]['option_id'] ?? null;
        $featureId = $this->variants[$index]['id'] ?? null;

        if (!$optionId || !$featureId) {
            return;
        }

        // 🔹 1️⃣ Validar duplicados dentro del formulario (evitar duplicaciones en el mismo request)
        $combinations = [];
        foreach ($this->variants as $i => $variant) {
            $combinationKey = $variant['option_id'] . '-' . $variant['id']; // Clave correcta
            if (isset($combinations[$combinationKey]) && $i != $index) {
                $fail("La combinación en la fila " . ($index + 1) . " ya existe en este formulario.");
                return;
            }
            $combinations[$combinationKey] = true;
        }

        // 🔹 2️⃣ Obtener todas las combinaciones de features ya registradas en este producto
        $existingVariants = DB::table('feature_variant')
            ->join('variants', 'feature_variant.variant_id', '=', 'variants.id')
            ->where('variants.product_id', $this->productId)
            ->selectRaw('feature_variant.variant_id, GROUP_CONCAT(feature_variant.feature_id ORDER BY feature_variant.feature_id) as features')
            ->groupBy('feature_variant.variant_id')
            ->pluck('features', 'variant_id')
            ->toArray();

        // 🔹 3️⃣ Obtener la combinación de features que se está tratando de registrar
        $newFeatureCombination = [];
        foreach ($this->variants as $variant) {
            $newFeatureCombination[] = $variant['id']; // Asegurar que es el feature_id correcto
        }
        sort($newFeatureCombination);
        $newFeatureCombinationStr = implode(',', $newFeatureCombination);

        // 🔹 4️⃣ Verificar si ya existe esta combinación exacta en otra variante del mismo producto
        foreach ($existingVariants as $existingCombination) {
            $existingFeatures = explode(',', $existingCombination);
            sort($existingFeatures);

            if ($newFeatureCombinationStr === implode(',', $existingFeatures)) {
                self::$duplicateRows[] = $index + 1;
            }
        }

        // 5️⃣ Mostrar solo un mensaje con todas las filas afectadas
        if (!empty(self::$duplicateRows) && $index == array_key_last($this->variants)) {
            self::$duplicateRows = array_unique(self::$duplicateRows); // Eliminar duplicados
            $fail("Las combinaciones en las filas " . implode(', ', self::$duplicateRows) . " ya existen en otra variante de este producto.");
        }
    }
}
