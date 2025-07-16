<?php

namespace App\Traits\Admin;

trait loadExtraData
{
    /**
     * Recorre y carga los modelos adicionales, que son pasador para create o edit
     */
    protected function loadExtraData()
    {
        $load = [];
        foreach ($this->extraData as $key => $model) {
            $load[$key] = $model::all();
        }
        return $load;
    }
}
