<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios'
    ],
]">
    @livewire('admin.users.user-index')
</x-admin-layout>