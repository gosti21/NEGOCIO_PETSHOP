<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas'
    ],
]">
    <x-slot name="action">
        @include('admin.partials.create-button', ['route' => route('admin.covers.create')])
    </x-slot>

    @if ($covers->count())
        <ul class="space-y-5" id="covers">
            @foreach ($covers as $cover)
                <li class="lg:flex overflow-hidden rounded-lg shadow-xl bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 cursor-move"
                    data-id="{{$cover->id}}">

                    <img src="{{ Storage::url($cover->images->first()->path) }}" alt="portada-{{$cover->title}}" class="w-full lg:w-64 aspect-[3/1] object-cover object-center">
                    
                    <div class="p-4 lg:flex-1 lg:flex lg:justify-between lg:items-center space-y-3 lg:space-y-0">
                        <div class="font-semibold">
                            <h1>
                                {{$cover->title}}
                            </h1>
                            <p>
                                @if ($cover->is_active)
                                    <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">
                                        Activo
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">
                                        Inactivo
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="lg:flex lg:flex-col lg:items-center">
                            <p class="text-sm font-bold">
                                Fecha de inicio
                            </p>
                            <p>
                                {{ $cover->start_at->format('d/m/y') }}
                            </p>
                        </div>
                        <div class="lg:flex lg:flex-col lg:items-center">
                            <p class="text-sm font-bold">
                                Fecha de fin
                            </p>
                            <p>
                                {{ $cover->end_at ? $cover->end_at->format('d/m/y') : '-' }}
                            </p>
                        </div>
                        <div class="flex lg:space-x-4 space-x-3">
                            <a href="{{ route('admin.covers.edit', $cover) }}" class="text-yellow-600 dark:text-yellow-500 lg:text-3xl text-2xl">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.covers.destroy', $cover) }}" method="POST" id="delete-form-{{ $cover->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-600 dark:text-red-500"
                                    onclick="confirmDelete({{ $cover->id }})">
                                    <span class="lg:text-3xl text-2xl">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        @include('admin.partials.sweet-alert-destroy')
    @else

        @include('admin.partials.alert-info', ['message' => 'Todavía no hay portadas registradas para la tienda virtual.'])

    @endif

    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.6/Sortable.min.js" integrity="sha512-csIng5zcB+XpulRUa+ev1zKo7zRNGpEaVfNB9On1no9KYTEY/rLGAEEpvgdw6nim1WdTuihZY1eqZ31K7/fZjw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            new Sortable(covers, {
                animation: 160,
                ghostClass: 'bg-blue-200',
                store: {
                    set: (sortable) => {
                        const orderSorts = sortable.toArray();
                        axios.post("{{ route('api.sort.orderCover') }}", {
                            sorts: orderSorts
                        }).catch((error) => {
                            console.log(error);
                        })
                    }
                }
            });
        </script>
    @endpush
</x-admin-layout>