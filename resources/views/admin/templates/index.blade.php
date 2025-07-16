<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => $breadcrumName
    ],
]">

    <x-slot name="action">
        @include('admin.partials.create-button', ['route' => route($route)])
    </x-slot>

    @if ($data->count())

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        @yield('headers')
                    </tr>
                </thead>
                <tbody>
                    @yield('content-table')
                </tbody>
            </table>
        </div>

        @include('admin.partials.sweet-alert-destroy')

        @if ($data->lastPage() > 1)
            <div class="mt-4">
                {{ $data->links() }}
            </div>
        @endif

    @else

        @include('admin.partials.alert-info', ['message' => $alertInfoMessage])

    @endif

</x-admin-layout>
