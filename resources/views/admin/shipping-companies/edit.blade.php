<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Couriers',
        'route' => route('admin.shipping-companies.index'),
    ],
    [
        'name' => 'Editar - ' . $data->name,
    ],
]">

    <div class="card card-color">
        <form action="{{ route('admin.shipping-companies.update', $data) }}" method="POST" id="edit-form">
            @csrf
            @method('PATCH')

            <x-validation-errors class="mb-4" />
            
            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre
                </x-label>
                <x-input class="w-full" 
                    placeholder="Ingrese el nombre de la compañía de envios" 
                    name="name" value="{{ old('name', $data->name) }}"/>
            </div>
            <div class="mb-4">
                <x-label class="mb-2">
                    Email
                </x-label>
                <x-input class="w-full" 
                    placeholder="Ingrese el email de la compañía de envios" 
                    name="email" value="{{ old('email', $data->email) }}" type="email"/>
            </div>
            <div class="mb-4">
                <x-label class="mb-2">
                    Teléfono
                </x-label>
                <x-input class="w-full" 
                    placeholder="965 455 123" 
                    name="phone" value="{{ old('phone', $data->phones->number) }}" type="number"/>
            </div>

            <div class="flex items-center mb-4">
                <hr class="flex-1">
                <span class="mx-3 text-black dark:text-white">
                    Dirección
                </span>
                <hr class="flex-1">
            </div>

            <div class="mb-4">
                <label for="district" class="dark:text-gray-200 text-gray-900">
                    Distrito
                </label>
                <x-input id="district" class="w-full mt-1" placeholder="Huancayo" name="district" value="{{ old('district', $data->district) }}"/>
            </div>
            
            <div class="mb-4">
                <label for="street" class="dark:text-gray-200 text-gray-900">
                    Calle
                </label>
                <x-input id="street" class="w-full mt-1" placeholder="Jr. los claves #2021" name="street" value="{{ old('street', $data->street) }}"/>
            </div>
            
            <div class="mb-4">
                <label for="reference" class="dark:text-gray-200 text-gray-900">
                    Referencia
                </label>
                <x-input id="reference" class="w-full mt-1" placeholder="Cerca al parque Túpac" name="reference" value="{{ old('reference', $data->reference) }}"/>
            </div>

            <div class="flex justify-end">
                <x-button type="button" onclick="confirmEdit()">
                    Actualizar
                </x-button>
            </div>
        </form>
    </div>

    @include('admin.partials.sweet-alert-edit')

</x-admin-layout>