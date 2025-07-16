<?php

namespace App\Rules\Admin;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class OptionProductFeatureExists implements ValidationRule
{
    protected $optionId;
    protected $productId;


    public function __construct($optionId, $productId)
    {
        $this->optionId = $optionId;
        $this->productId = $productId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table('option_product')
            ->whereJsonContains('features', ['id' => $value])
            ->where('option_id', $this->optionId)
            ->where('product_id', $this->productId)
            ->exists();

        if ($exists) {
            $fail("El valor ingresado ya se encuentra registrado");
        }
    }
}
