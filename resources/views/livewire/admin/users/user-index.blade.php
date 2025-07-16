<div>
    <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-4 mb-5">
        <div class="block col-span-1">
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <span class="text-gray-500 dark:text-gray-400" aria-hidden="true">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                </div>
                <x-search placeholder="Buscar por nombres o email" wire:model.live="search"></x-search>
            </div>
        </div>
        <div class="flex items-center col-span-1 justify-self-end">
            <span class="mr-2 text-gray-900 dark:text-white font-semibold">
                Ordenar por:
            </span>
    
            <x-select wire:model.live="selectBy" class="w-full"> 
                <option value="1" wire:key="customers">Clientes</option>
                <option value="2" wire:key="employees">Empleados</option>
            </x-select>
        </div>
    </div>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        @if (count($users))
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nombres
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Rol
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 {{ !$loop->last ? 'border-b dark:border-gray-700 border-gray-200' : '' }}">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $user->name}} {{ $user->last_name}}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->email}}
                            </td>
                            <td class="px-6 py-4">
                                @if (count($user->roles))
                                    Admin
                                @else
                                    No tiene rol
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            @include('admin.partials.alert-info', ['message' => 'No hay ningún registro coincidente'])
        @endif
    </div>
    @if ($users->lastPage() > 1)
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    @endif
</div>
