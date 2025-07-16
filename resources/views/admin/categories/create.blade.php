<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
        'route' => route('admin.categories.index'),
    ],
    [
        'name' => 'Crear',
    ],
]">

    <div class="card card-color">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label class="mb-2">
                    Familias
                </x-label>

                <x-select name="family_id" class="w-full">
                    <option disabled selected>Selecciona una familia </option>
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}"
                            @selected(old('family_id') == $family->id)>
                            {{ $family->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre
                </x-label>
                <x-input class="w-full" 
                    placeholder="Ingrese el nombre de la categoría" 
                    name="name" value="{{ old('name') }}"/>
            </div>
            <div class="flex justify-end">
                <x-button>
                    Guardar
                </x-button>
            </div>
        </form>
    </div>
    
</x-admin-layout>
