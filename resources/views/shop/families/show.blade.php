<x-app-layout>
    <x-container class="px-4 my-4">
        <div>
            @include('shop.partials.breadcrumb', [
                'breadcrumbs' => [
                    [
                        'name' => $family->name
                    ]
                ]
            ])
        </div>
    </x-container>
    @livewire('shop.filter', [
        'family_id' => $family->id,
    ])
</x-app-layout>
