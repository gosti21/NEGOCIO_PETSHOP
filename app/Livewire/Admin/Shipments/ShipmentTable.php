<?php

namespace App\Livewire\Admin\Shipments;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Shipment;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ShipmentTable extends DataTableComponent
{
    protected $model = Shipment::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("#", "id")
                ->sortable(),
            Column::make("N° de orden", "order_id")
                ->sortable(),
            Column::make("Courier", "shippingCompanies.name")
                ->sortable(),
            Column::make("N° de seguimiento", "tracking_number")
                ->sortable(),
            Column::make("Estado", "status")
                ->format(function ($value){
                    return $value->name;
                }),
            Column::make('actions')
                ->label(function ($row){
                    return view('admin.shipments.actions', [
                        'shipment' => $row
                    ]);
                })
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Estado')
                ->options([
                    '' => 'todos',
                    1 => 'Pendiente',
                    2 => 'Enviando',
                    3 => 'Recibido',
                    4 => 'Fallido',
                ])->filter(function($query, $value){
                    $query->where('status', $value);
                })
        ];
    }

    public function markAsSending(Shipment $shipment)
    {
        $shipment->status = ShipmentStatus::Enviando;
        $shipment->shipped_at = now();
        $shipment->save();
    }

    public function markAsCompleted(Shipment $shipment)
    {
        $shipment->status = ShipmentStatus::Recibido;
        $shipment->delivered_at = now();
        $shipment->save();

        $order = $shipment->order;
        $order->status = OrderStatus::Recibido;
        $order->save();
    }

    public function markAsFailed(Shipment $shipment)
    {
        $shipment->status = ShipmentStatus::Fallido;
        $shipment->save();

        $order = $shipment->order;
        $order->status = OrderStatus::Fallido;
        $order->save();
    }
}
