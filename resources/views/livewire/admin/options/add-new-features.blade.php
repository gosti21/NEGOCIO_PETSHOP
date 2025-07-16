
<div>
    <form wire:submit="addFeature" class="flex space-x-4">
        <div class="flex-1">
            <x-label class="mb-2">
                Valor:
            </x-label>
    
            @switch($option->type)
                @case(1)
                    <x-input wire:model="newFeature.value" class="w-full"
                        placeholder="Ingrese el valor de la opción" />
                @break
    
                @case(2)
                    <div
                        class="border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300  rounded-md shadow-sm h-[42px] flex items-center px-3 justify-between">
    
                        {{ $newFeature['value'] ?: 'Seleccione un color' }}
    
                        <input type="color" wire:model.live="newFeature.value"
                            class="focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    </div>
                @break
    
                @default
                    <div
                        class="border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300  rounded-md shadow-sm h-[42px] flex items-center px-3 justify-between">
                        <p>Seleccione un tipo primero</p>
                    </div>
            @endswitch
        </div>
    
        <div class="flex-1">
            <x-label class="mb-2">
                Descripción:
            </x-label>
                                    
            <x-input wire:model="newFeature.description" class="w-full" placeholder="Ingrese una descripción"/>
        </div>
    
        <div class="pt-11">
            <button>
                <i class="fa-solid fa-circle-plus fa-2xl text-yellow-500 hover:text-yellow-400 dark:text-yellow-300 dartk:hover:text-yellow-400"></i>
            </button>
        </div>
    </form>

    <x-validation-errors class="mt-4" />

</div>
