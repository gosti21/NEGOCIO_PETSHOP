<?php

namespace App\Observers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class OrderObserver
{
    public function created(Order $order)
    {
        Storage::makeDirectory('tickets');

        $pdf = Pdf::loadView('shop.orders.ticket', compact('order'))->setPaper('a5');

        $pdf->save(storage_path('app/public/tickets/ticket-' . $order->id . '.pdf'));

        $order->pdf_path = 'tickets/ticket-' . $order->id . '.pdf';
        $order->save();
    }
}
