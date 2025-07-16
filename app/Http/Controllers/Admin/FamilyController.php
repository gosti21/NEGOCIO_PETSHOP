<?php

namespace App\Http\Controllers\Admin;

use App\Models\Family;
use App\Repositories\Admin\BaseRepository;

class FamilyController extends BaseAdminController
{
    public function __construct()
    {
        $model = new Family();
        $viewName = 'families';

        $repository = new BaseRepository($model, $viewName);

        $repository->setRelationChecker(function ($family) {
            return $family->categories()->count() > 0;
        });

        parent::__construct($repository);
    }
}
