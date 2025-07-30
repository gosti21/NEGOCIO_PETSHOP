<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    protected $fillable = [
        'name',
        'type'
    ];

    /**
     * Verificar si esta presente la familia, para traer todos los features relacionadas a esta
     */
    public function scopeVerifyFamily($query, $family_id)
    {
        $query->when($family_id, function ($query, $family_id) {
            $query->whereHas('products.subCategory.category', function ($query) use ($family_id) {
                $query->where('family_id', $family_id);
            })
            ->with([
                'features' => function ($query) use ($family_id) {
                    $query->whereHas('variants.product.subCategory.category', function ($query) use ($family_id) {
                        $query->where('family_id', $family_id);
                    });
                }
            ]);
        });
    }

    /**
     * Verificar si esta presente la categoria, para traer todos los features relacionadas a esta
     */
    public function scopeVerifyCategory($query, $category_id)
    {
    $query->when($category_id, function ($query, $category_id) {
        $query->whereHas('products', function ($query) use ($category_id) {
            $query->where('category_id', $category_id);
        })
        ->with([
            'features' => function ($query) use ($category_id) {
                $query->whereHas('variants.product', function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                });
            }
        ]);
    });
}

    /**
     * Verificar si esta presente la subcategoria, para traer todos los features relacionadas a esta
     */
    public function scopeVerifySubCategory($query, $subcategory_id)
    {
        $query->when($subcategory_id, function ($query, $subcategory_id) {
            $query->whereHas('products', function ($query) use ($subcategory_id) {
                $query->where('sub_category_id', $subcategory_id);
            })
            ->with([
                'features' => function ($query) use ($subcategory_id) {
                    $query->whereHas('variants.product', function ($query) use ($subcategory_id){
                        $query->where('sub_category_id', $subcategory_id);
                    });
                }
            ]);
        });
    }

    // RelationShips
    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(OptionProduct::class)
            ->withPivot('features')
            ->withTimestamps();
    }
}
