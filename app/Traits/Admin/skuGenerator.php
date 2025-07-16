<?php

namespace App\Traits\Admin;

use App\Models\SubCategory;

trait skuGenerator
{
    /**
     * Genera el Sku del producto, en base a su subCategory, name y numRandoms
     */
    public function generateSku( string $subCategory,  string $name )
    {
        $subCategoryName = SubCategory::find($subCategory)->name;

        $subCategoryPrefix = strtoupper(substr($subCategoryName, 0, 3));
        $namePrefix = strtoupper(substr($name, 0, 3));

        $randomNumbers = rand(1000, 9999);
        
        return $subCategoryPrefix . $namePrefix . $randomNumbers;
    }

    public function generateSkuVariant( string $name)
    {
        $prefix = strtoupper('var');
        $randomNumbers = rand(1000, 9999);
        $namePrefix = strtoupper(substr($name, 0, 3));

        return $prefix . $randomNumbers . $namePrefix;
    }
}
