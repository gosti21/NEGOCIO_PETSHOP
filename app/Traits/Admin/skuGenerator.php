<?php

namespace App\Traits\Admin;

trait skuGenerator
{
    /**
     * Genera el SKU del producto, en base al nombre de la categoría, nombre del producto y un número aleatorio
     */
    public function generateSku(string $categoryName, string $productName): string
    {
        $categoryPrefix = strtoupper(substr($categoryName, 0, 3));
        $namePrefix = strtoupper(substr($productName, 0, 3));
        $randomNumbers = rand(1000, 9999);

        return $categoryPrefix . $namePrefix . $randomNumbers;
    }

    /**
     * Genera un SKU para una variante del producto
     */
    public function generateSkuVariant(string $name): string
    {
        $prefix = strtoupper('var');
        $randomNumbers = rand(1000, 9999);
        $namePrefix = strtoupper(substr($name, 0, 3));

        return $prefix . $randomNumbers . $namePrefix;
    }
}
