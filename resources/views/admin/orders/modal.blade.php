<x-dialog-modal wire:model="newShipment.openModal" wire:key="modal">
    <x-slot name="title">
        Asignar empresa de envío
    </x-slot>
    <x-slot name="content">
        <x-validation-errors class="mb-4" />
        <div class="mb-4">
            <x-label class="mb-2">
                Nombre del courier
            </x-label>
            <x-select class="w-full" wire:model="newShipment.shipping_companies_id" wire:key="shipping_companies_id">
                <option value="" disabled selected>Seleccione un courier</option>
                @foreach ($couriers as $courier)
                    <option value="{{ $courier->id }}">
                        {{ $courier->name }}
                    </option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-label class="mb-2">
                Número de seguimiento
            </x-label>
            <x-input class="w-full" placeholder="Digite el N° de seguimiento" wire:model="newShipment.tracking_number"></x-input>
        </div>
    </x-slot>
    <x-slot name="footer">
        <button class="btn btn-red" wire:click="$set('newShipment.openModal', false)" wire:key="cancelar-courier">
            Cancelar
        </button>
        <button class="btn btn-blue ml-3" wire:click="saveCourier" wire:key="asignar-courier">
            Asignar
        </button>
    </x-slot>
</x-dialog-modal>