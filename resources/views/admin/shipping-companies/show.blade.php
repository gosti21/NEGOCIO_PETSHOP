<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Couriers',
        'route' => route('admin.shipping-companies.index'),
    ],
    [
        'name' => $data->name,
    ],
]">

    <section class="mb-8">
        <h5 class="text-center header-h5 mb-4">Detalles de la empresa de envíos</h5>

        <div>
            <div class="mb-3">
                <h6 class="header-h6">
                    Nombre:
                </h6>
                <p class="text-gray-500 dark:text-gray-300">
                    {{ $data->name }}
                </p>
            </div>

            <div class="mb-3">
                <h6 class="header-h6">
                    Email:
                </h6>
                <p class="cont-p">
                    {{ $data->email }}
                </p>
            </div>

            <div class="mb-3">
                <h6 class="header-h6">
                    Teléfono:
                </h6>
                <p class="cont-p">
                    {{ $data->phones->prefix }} {{ $data->phones->number }}
                </p>
            </div>

            <div class="mb-3">
                <h6 class="header-h6">
                    Distrito:
                </h6>
                <p class="cont-p">
                    {{ $data->district }}
                </p>
            </div>

            <div class="mb-3">
                <h6 class="header-h6">
                    Calle:
                </h6>
                <p class="cont-p">
                    {{ $data->street }}
                </p>
            </div>

            <div class="mb-3">
                <h6 class="header-h6">
                    Referencia:
                </h6>
                <p class="cont-p">
                    {{ $data->reference }}
                </p>
            </div>
        </div>
    </section>

</x-admin-layout>