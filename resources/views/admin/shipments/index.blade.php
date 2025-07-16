<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Envios'
    ],
]">
    @livewire('admin.shipments.shipment-table')
</x-admin-layout>