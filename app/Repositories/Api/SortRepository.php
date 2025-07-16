<?php

namespace App\Repositories\Api;

use App\Models\Cover;

class SortRepository
{
    public function orderCover($request)
    {
        $sorts = $request->get('sorts');

        $order = 1;
        foreach ($sorts as $sort) {
            $cover = Cover::findOrFail($sort);

            $cover->order = $order;
            $cover->save();

            $order++;
        }
    }
}