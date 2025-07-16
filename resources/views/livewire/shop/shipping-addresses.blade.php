<div>
    <section class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border-gray-200 dark:border-gray-700 mx-3 md:mx-0">
        <header class="bg-gray-900 dark:bg-gray-200 px-4 py-2">
            <h2 class="text-white dark:text-black text-lg font-medium">
                Direcciones de envío guardadas
            </h2>
        </header>

        <div class="p-4">
            @if ($newAddress)

                <x-validation-errors class="mb-4" />

                <div class="grid grid-cols-4 gap-4">
                    <div class="col-span-2">
                        <label for="province" class="dark:text-gray-200 text-gray-900">
                            Provincia
                        </label>
                        <x-input id="province" class="w-full mt-1" wire:model="createAddress.province" wire:key="province" placeholder="Ingrese el nombre de su provincia"/>
                    </div>
                    <div class="col-span-2">
                        <label for="district" class="dark:text-gray-200 text-gray-900">
                            Distrito
                        </label>
                        <x-input id="district" class="w-full mt-1" wire:model="createAddress.district" wire:key="district" placeholder="Ingrese el nombre de su distrito"/>
                    </div>
                    
                    <div class="col-span-2">
                        <label for="street" class="dark:text-gray-200 text-gray-900">
                            Calle
                        </label>
                        <x-input id="street" class="w-full mt-1" wire:model="createAddress.street" wire:key="street" placeholder="Jr. los claves #2021"/>
                    </div>
                    <div class="col-span-2">
                        <label for="reference" class="dark:text-gray-200 text-gray-900">
                            Referencia
                        </label>
                        <x-input id="reference" class="w-full mt-1" wire:model="createAddress.reference" wire:key="reference" placeholder="Cerca al parque Túpac"/>
                    </div>
                </div>

                <hr class="my-6">

                <div x-data="{
                    receiver: @entangle('createAddress.receiver'),        
                    receiver_info: @entangle('createAddress.receiver_info'),        
                }" x-init="
                    $watch('receiver', value => {
                        if(value == 1){
                            receiver_info.name = '{{ Auth::user()->name }}';
                            receiver_info.last_name = '{{ Auth::user()->last_name }}';
                            receiver_info.document_type = '{{ Auth::user()->documents->document_type }}';
                            receiver_info.document_number = '{{ Auth::user()->documents->document_number }}';
                            receiver_info.phone = '{{ Auth::user()->phones->number }}';
                        }else{
                            receiver_info.name = '';
                            receiver_info.last_name = '';
                            receiver_info.document_number = '';
                            receiver_info.phone = '';
                        }
                    })
                ">
                    <p class="font-semibold mb-3 dark:text-white text-black">
                        ¿Quién recibirá el pedido?
                    </p>

                    <div class="flex space-x-8 dark:text-gray-300 text-gray-800 mb-4">
                        <label class="flex items-center">
                            <x-radio value="1" class="mr-2" x-model="receiver"/>
                            Sere yó
                        </label>
                        
                        <label class="flex items-center">
                            <x-radio value="2" class="mr-2" x-model="receiver"/>
                            Otra persona
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="dark:text-gray-200 text-gray-900">
                            <label for="name">
                                Nombres
                            </label>
                            <x-input x-bind:disabled="receiver == 1" x-model="receiver_info.name" id="name" class="w-full mt-1" placeholder="Ingrese sus nombres"/>
                        </div>
                        <div class="dark:text-gray-200 text-gray-900">
                            <label for="last_name">
                                Apellidos
                            </label>
                            <x-input x-bind:disabled="receiver == 1" x-model="receiver_info.last_name" id="last_name" class="w-full mt-1" placeholder="Ingrese sus apellidos"/>
                        </div>
                        <div class="dark:text-gray-200 text-gray-900">
                            <label for="document">
                                Documento
                            </label>

                            <div class="flex space-x-3">
                                <x-select class="mt-1" x-model="receiver_info.document_type" x-bind:disabled="receiver == 1">
                                    @foreach (\App\Enums\TypeOfDocuments::cases() as $docsType)
                                        <option value="{{ $docsType->value }}" @selected(old('document_type') == $docsType->value)>
                                            {{ $docsType->label() }}
                                        </option>
                                    @endforeach
                                </x-select>

                                <x-input x-bind:disabled="receiver == 1" x-model="receiver_info.document_number" id="document" class="w-full mt-1" placeholder="Ingre su N° de documento"/>
                            </div>
                        </div>
                        <div class="dark:text-gray-200 text-gray-900">
                            <label for="phone">
                                Teléfono
                            </label>
                            <x-input x-bind:disabled="receiver == 1" x-model="receiver_info.phone" id="phone" class="w-full mt-1" type="number" placeholder="985123405"/>
                        </div>
                        <div>
                            <button class="w-full mt-2 btn btn-red"
                                wire:click="$set('newAddress', false)">
                                Cancelar
                            </button>
                        </div>
                        <div>
                            <button class="w-full mt-2 btn btn-blue"
                                wire:click="store">
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            @else

                @if ($editAddress->id)
                    <x-validation-errors class="mb-4" />

                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-2">
                            <label for="province" class="dark:text-gray-200 text-gray-900">
                                Provincia
                            </label>
                            <x-input id="province" class="w-full mt-1" wire:model="editAddress.province" wire:key="province" placeholder="Ingrese el nombre de su provincia"/>
                        </div>
                        <div class="col-span-2">
                            <label for="district" class="dark:text-gray-200 text-gray-900">
                                Distrito
                            </label>
                            <x-input id="district" class="w-full mt-1" wire:model="editAddress.district" wire:key="district" placeholder="Ingrese el nombre de su distrito"/>
                        </div>
                        
                        <div class="col-span-2">
                            <label for="street" class="dark:text-gray-200 text-gray-900">
                                Calle
                            </label>
                            <x-input id="street" class="w-full mt-1" wire:model="editAddress.street" wire:key="street" placeholder="Jr. los claves #2021"/>
                        </div>
                        <div class="col-span-2">
                            <label for="reference" class="dark:text-gray-200 text-gray-900">
                                Referencia
                            </label>
                            <x-input id="reference" class="w-full mt-1" wire:model="editAddress.reference" wire:key="reference" placeholder="Cerca al parque Túpac"/>
                        </div>
                    </div>

                    <hr class="my-6">

                    <div x-data="{
                        receiver: @entangle('editAddress.receiver'),        
                        receiver_info: @entangle('editAddress.receiver_info'),        
                    }" x-init="
                        $watch('receiver', value => {
                            if(value == 1){
                                receiver_info.name = '{{ Auth::user()->name }}';
                                receiver_info.last_name = '{{ Auth::user()->last_name }}';
                                receiver_info.document_type = '{{ Auth::user()->documents->document_type }}';
                                receiver_info.document_number = '{{ Auth::user()->documents->document_number }}';
                                receiver_info.phone = '{{ Auth::user()->phones->number }}';
                            }else{
                                receiver_info.name = '';
                                receiver_info.last_name = '';
                                receiver_info.document_number = '';
                                receiver_info.phone = '';
                            }
                        })
                    ">
                        <p class="font-semibold mb-3 dark:text-white text-black">
                            ¿Quién recibirá el pedido?
                        </p>

                        <div class="flex space-x-8 dark:text-gray-300 text-gray-800 mb-4">
                            <label class="flex items-center">
                                <x-radio value="1" class="mr-2" x-model="receiver"/>
                                Sere yó
                            </label>
                            
                            <label class="flex items-center">
                                <x-radio value="2" class="mr-2" x-model="receiver"/>
                                Otra persona
                            </label>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="dark:text-gray-200 text-gray-900">
                                <label for="name">
                                    Nombres
                                </label>
                                <x-input x-bind:disabled="receiver == 1" x-model="receiver_info.name" id="name" class="w-full mt-1" placeholder="Ingrese sus nombres"/>
                            </div>
                            <div class="dark:text-gray-200 text-gray-900">
                                <label for="last_name">
                                    Apellidos
                                </label>
                                <x-input x-bind:disabled="receiver == 1" x-model="receiver_info.last_name" id="last_name" class="w-full mt-1" placeholder="Ingrese sus apellidos"/>
                            </div>
                            <div class="dark:text-gray-200 text-gray-900">
                                <label for="document">
                                    Documento
                                </label>

                                <div class="flex space-x-3">
                                    <x-select class="mt-1" x-model="receiver_info.document_type" x-bind:disabled="receiver == 1">
                                        @foreach (\App\Enums\TypeOfDocuments::cases() as $docsType)
                                            <option value="{{ $docsType->value }}" @selected(old('document_type') == $docsType->value)>
                                                {{ $docsType->label() }}
                                            </option>
                                        @endforeach
                                    </x-select>

                                    <x-input x-bind:disabled="receiver == 1" x-model="receiver_info.document_number" id="document" class="w-full mt-1" placeholder="Ingre su N° de documento"/>
                                </div>
                            </div>
                            <div class="dark:text-gray-200 text-gray-900">
                                <label for="phone">
                                    Teléfono
                                </label>
                                <x-input x-bind:disabled="receiver == 1" x-model="receiver_info.phone" id="phone" class="w-full mt-1" type="number" placeholder="985123405"/>
                            </div>
                            <div>
                                <button class="w-full mt-2 btn btn-red"
                                    wire:click="$set('editAddress.id', null)">
                                    Cancelar
                                </button>
                            </div>
                            <div>
                                <button class="w-full mt-2 btn btn-blue"
                                    wire:click="update">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    @if ($addresses->count())
                        <ul class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ($addresses as $index => $address)
                                <li class="{{ $address->default ? 'bg-gray-100 dark:bg-gray-700 shadow-lg' : 'dark:bg-gray-800 border dark:border-gray-300 border-gray-900' }} rounded-lg"
                                    wire:click="addresses-{{ $address->id }}">
                                    <div class="p-3 flex items-center">
                                        <div>
                                            <i class="fa-solid fa-location-dot fa-lg text-yellow-500 dark:text-yellow-300"></i>
                                        </div>
                                        <div class="flex-1 mx-4 text-sm">
                                            <p class="font-semibold text-yellow-500 dark:text-yellow-300">
                                                Dirección N° {{$index+1}}
                                            </p>
                                            
                                            <p class="text-gray-800 dark:text-gray-100 font-semibold">
                                                {{ $address->district }}
                                            </p>
                                            
                                            <p class="text-gray-800 dark:text-gray-100 font-semibold">
                                                {{ $address->street }}
                                            </p>
                                            
                                            <p class="text-gray-800 dark:text-gray-100 font-semibold">
                                                {{ $address->receiver_info['name'] }}
                                            </p>
                                        </div>
                                        <div class="text-gray-700 dark:text-gray-200 flex flex-col">
                                            <button wire:click="setDefaultAddress({{ $address->id }})" wire:key="default-{{ $address->id }}">
                                                <i class="fa-solid fa-star fa-xs {{ $address->default ? 'text-yellow-500 dark:text-yellow-300' : '' }} hover:text-yellow-500 hover:dark:text-yellow-300"></i>
                                            </button>
                                            <button wire:click="edit({{ $address->id }})" wire:key="edit-{{ $address->id }}">
                                                <i class="fa-solid fa-pencil fa-xs hover:text-green-400"></i>
                                            </button>
                                            <button wire:click="deleteAddress({{ $address->id }})" wire:key="delete-{{ $address->id }}">
                                                <i class="fa-solid fa-trash-can fa-xs hover:text-red-500"></i>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center dark:text-white">
                            No se han encontrado direcciones
                        </p>
                    @endif

                    <button class="btn btn-blue w-full flex items-center justify-center mt-4 mb-0"
                        wire:click="$set('newAddress', true)">
                        Agregar
                        <i class="fa-solid fa-plus ml-2"></i>
                    </button>
                @endif

            @endif
        </div>
    </section>
</div>
