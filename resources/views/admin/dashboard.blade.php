<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
    ],
]">
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 text-gray-900 dark:text-white">
        <!-- Bienvenida -->
        <div class="rounded-2xl shadow-md p-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-4">
                <img class="w-14 h-14 rounded-full object-cover ring-2 ring-[#FEC51C]" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                <div class="flex-1">
                    <h2 class="text-xl font-semibold">
                        ¡Bienvenido, {{ auth()->user()->name }}!
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Esperamos que tengas un gran día de gestión.
                    </p>

                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button class="text-sm text-red-500 hover:underline">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel decorativo o informativo -->
        <div class="rounded-2xl shadow-md p-6 bg-gradient-to-br from-yellow-300 to-yellow-500 dark:from-[#FEC51C] dark:to-yellow-600 text-center text-black dark:text-white flex items-center justify-center">
            <div>
                <h2 class="text-2xl font-bold">Panel de Administración</h2>
                <p class="text-sm mt-2">
                    Gestiona familias, categorías, productos, usuarios y más.
                </p>
            </div>
        </div>
    </section>
</x-admin-layout>
