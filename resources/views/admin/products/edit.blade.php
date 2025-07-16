<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
        'route' => route('admin.products.index'),
    ],
    [
        'name' => 'Editar - ' . $data->name,
    ],
]">

    @livewire('admin.products.product-edit', compact('data'))

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