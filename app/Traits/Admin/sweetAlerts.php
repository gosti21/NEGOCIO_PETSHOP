<?php

namespace App\Traits\Admin;

trait sweetAlerts
{
    /**
     * Genera alertas para los baserepository
     */
    public function alertGenerate1(array $params = [])
    {
        $defaultParams = [
            'title' => '¡Registro creado!',
            'text' => 'El registro ha sido creado correctamente.',
            'icon' => 'success',
            'timer' => 1600,
            'timerProgressBar' => true,
        ];

        if(!empty($params)){
            // Combina los valores por defecto con los valores proporcionados en $params
            $alertParams = array_merge($defaultParams, $params);
        }else{
            $alertParams = $defaultParams;
        }

        // Envía el mensaje a la sesión
        session()->flash('swal', $alertParams);
    }

    /**
     * Genera las alertas para livewire
     */
    public function alertGenerate2(array $params = [])
    {
        $defaultParams = [
            'title' => '¡Registro creado!',
            'text' => 'El registro ha sido creado correctamente.',
            'icon' => 'success',
            'timer' => 1800,
            'timerProgressBar' => true,
        ];

        if (!empty($params)) {
            // Combina los valores por defecto con los valores proporcionados en $params
            $alertParams = array_merge($defaultParams, $params);
        } else {
            $alertParams = $defaultParams;
        }
        
        $this->dispatch('swal', $alertParams);
    }
}
