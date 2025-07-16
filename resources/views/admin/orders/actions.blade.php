<div class="flex flex-col space-y-2">
    @switch($order->status)
        @case(\App\Enums\OrderStatus::Pendiente)
            <button class="underline text-blue-500 hover:no-underline" wire:click="markAsProcessing({{ $order->id }})">
                Alistar producto
            </button>
            @break
        @case(\App\Enums\OrderStatus::Alistando)
            <button wire:click="assignShippingCompany({{ $order->id }})" 
                class="underline text-blue-500 hover:no-underline">
                Enviar producto
            </button>
            @break
        @case(\App\Enums\OrderStatus::Fallido)
            <button wire:click="markAsRefunded({{ $order->id }})" 
                class="underline text-blue-500 hover:no-underline">
                Marcar como devuelto
            </button>
            @break
        @case(\App\Enums\OrderStatus::Devuelto)
            <button wire:click="assignShippingCompany({{ $order->id }})" 
                class="underline text-blue-500 hover:no-underline">
                Enviar producto
            </button>
            @break
        @default
    @endswitch
    @if ($order->status != \App\Enums\OrderStatus::Cancelado && $order->status != \App\Enums\OrderStatus::Recibido)
        <button class="underline text-red-500 hover:no-underline"
            wire:click="cancelOrder({{ $order->id }})" wire:key="cancel">
            Cancelar
        </button>
    @endif
</div>