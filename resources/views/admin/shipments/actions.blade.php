@switch($shipment->status)
    @case(\App\Enums\ShipmentStatus::Pendiente)
        <button class="underline text-blue-500 hover:no-underline" wire:click="markAsSending({{ $shipment->id }})" wire:key="sending">
            En camino
        </button>
        @break
    @case(\App\Enums\ShipmentStatus::Enviando)
        <div class="flex flex-col space-y-2">
            <button class="underline text-blue-500 hover:no-underline" wire:click="markAsCompleted({{ $shipment->id }})" wire:key="recibido">
                Recibido
            </button>
            <button class="underline text-red-500 hover:no-underline" wire:key="error" wire:click="markAsFailed({{ $shipment->id }})">
                Error en la entrega
            </button>
        </div>
        @break
    @default
@endswitch
