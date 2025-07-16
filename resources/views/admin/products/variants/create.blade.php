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
        'name' => $product->name,
        'route' => route('admin.products.show', $product->id),
    ],
    [
        'name' => 'Crear Variantes',
    ],
]">

    @livewire('admin.products.variants.variant-create', compact('product'))

</x-admin-layout>