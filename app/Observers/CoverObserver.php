<?php

namespace App\Observers;

use App\Models\Cover;

class CoverObserver
{
    /**
     * Escucha cuando se crea un nuevo cover y aplica lo definido
     */
    public function creating(Cover $cover): void
    {
        $cover->order = Cover::max('order') + 1 ;
    }

    /**
     * Escucha cuando se elimina un cover y actualiza el orden de los demás covers.
     */
    public function deleted(Cover $cover): void
    {
        // Obtener el orden del cover eliminado
        $deletedOrder = $cover->order;

        // Actualizar los covers con orden mayor al eliminado
        Cover::where('order', '>', $deletedOrder)
            ->decrement('order'); // Decrementamos el order en 1 para los covers restantes
    }
}
