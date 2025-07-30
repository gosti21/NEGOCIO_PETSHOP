<x-app-layout>
    <x-container class="px-4 my-4">
        <div>
            @include('shop.partials.breadcrumb', [
                'breadcrumbs' => [
                    [
                        'name' => $product->category->family->name,
                        'route' => route('families.show', $product->category->family)
                    ],
                    [
                        'name' => $product->category->name,
                        'route' => route('categories.show', $product->category)
                    ],
                    [
                        'name' => $product->name
                    ]
                ]
            ])
        </div>
    </x-container>

    @livewire('shop.products.add-to-cart', ['product' => $product])
</x-app-layout>
