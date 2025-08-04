<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'stock',
        'category_id', // ✅ si ya lo añadiste a la tabla
    ];

    /**
     * Verificar si está presente la categoría
     */
    public function scopeVerifyCategory($query, $category_id)
    {
        $query->when($category_id, function ($query, $category_id) {
            $query->where('category_id', $category_id);
        });
    }

    /**
     * Verificar si está presente la familia (usando relación en Category si la defines)
     * Opcional: Puedes quitar si no lo necesitas
     */
    public function scopeVerifyFamily($query, $family_id)
    {
        $query->when($family_id, function ($query, $family_id) {
            $query->whereHas('category', function ($query) use ($family_id) {
                $query->where('family_id', $family_id);
            });
        });
    }

    public function scopeCustomOrder($query, $orderBy)
    {
        $query->when($orderBy == 1, function ($query) {
            $query->orderBy('created_at', 'desc');
        })
        ->when($orderBy == 2, function ($query) {
            $query->join('variants', 'variants.product_id', '=', 'products.id')
                ->orderBy('variants.price', 'desc');
        })
        ->when($orderBy == 3, function ($query) {
            $query->join('variants', 'variants.product_id', '=', 'products.id')
                ->orderBy('variants.price', 'asc');
        });
    }

    public function scopeSelectFeatures($query, $select_features)
    {
        $query->when($select_features, function ($query, $select_features) {
            $query->whereHas('variants.features', function ($query) use ($select_features) {
                $query->whereIn('features.id', $select_features);
            });
        });
    }

    public function scopeVerifyProduct($query, $family_id = null, $category_id = null)
{
    return $query
        ->when($family_id, function ($query, $family_id) {
            $query->whereHas('category.family', function ($query) use ($family_id) {
                $query->where('id', $family_id);
            });
        })
        ->when($category_id, function ($query, $category_id) {
            $query->where('category_id', $category_id);
        });
}

    public function scopeSearch($query, $search)
    {
        $query->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    // Relación con imágenes
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->chaperone();
    }

    // ✅ Nueva relación directa con categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class)
                    ->using(OptionProduct::class)
                    ->withPivot('features')
                    ->withTimestamps();
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }
}
