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
     * Verificar si está presente la familia, para traer todos los features relacionados a esta.
     */
    public function scopeVerifyFamily($query, $family_id)
    {
        $query->when($family_id, function ($query, $family_id) {
            $query->whereHas('products.category', function ($query) use ($family_id) {
                $query->where('family_id', $family_id);
            })
            ->with([
                'features' => function ($query) use ($family_id) {
                    $query->whereHas('variants.product.category', function ($query) use ($family_id) {
                        $query->where('family_id', $family_id);
                    });
                }
            ]);
        });
    }

    /**
     * Verificar si está presente la categoría, para traer todos los features relacionados a esta.
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
