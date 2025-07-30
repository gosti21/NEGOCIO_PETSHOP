{{--<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'SubCategorías',
        'route' => route('admin.subcategories.index'),
    ],
    [
        'name' => 'Editar - ' . $data->name,
    ],
]">

    @livewire('admin.subcategories.subcategory-edit', compact('data'))

    @push('js')
        <script>
            Livewire.on('subcategoryUpdated', newName => {
                // Selecciona todos los elementos con la clase 'breadcrumb-edit'
                const breadcrumbElements = document.querySelectorAll('.breadcrumb-edit');

                // Itera sobre los elementos y actualiza su texto
                breadcrumbElements.forEach(element => {
                    element.innerText = 'Editar - ' + newName;
                });
            });
        </script>
    @endpush

</x-admin-layout>
