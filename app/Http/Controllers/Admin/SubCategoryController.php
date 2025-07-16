<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubCategory;
use App\Repositories\Admin\BaseRepository;

class SubCategoryController extends BaseAdminController
{
    public function __construct()
    {
        $model = new SubCategory();
        $viewName = 'subcategories';
        $relationships = [
            'category.family',
        ];

        $repository = new BaseRepository($model, $viewName, $relationships);

        $repository->setRelationChecker(function ($subcategory) {
            return $subcategory->products()->count() > 0;
        });

        parent::__construct($repository);
    }
}
