<div>
    <form wire:submit="save" class="flex space-x-4">
        @foreach ($option->features as $index => $feature)
            <div class="flex-1">
                <x-label class="mb-2">
                    Valor:
                </x-label>

                <x-select wire:model="variant.features.{{ $index }}.id" wire:key="variant.features.{{ $index }}" class="w-full">
                    <option disabled selected value="">Seleccione un valor</option>
                    <option value="{{ $feature->id }}">
                        {{ $feature->description }}
                    </option>
                </x-select>
            </div>
        @endforeach

        <div class="pt-11">
            <button type="submit">
                <i class="fa-solid fa-circle-plus fa-2xl text-yellow-500 hover:text-yellow-400 dark:text-yellow-300 dark:hover:text-yellow-400"></i>
            </button>
        </div>
    </form>

    <x-validation-errors class="mt-4" />
</div>
