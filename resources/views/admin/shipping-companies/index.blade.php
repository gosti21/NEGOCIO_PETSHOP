@extends('admin.templates.index')
@php
    $breadcrumName = 'Couriers';
    $route = 'admin.shipping-companies.create';
    $alertInfoMessage = 'Todavía no hay empresas de envío registradas.';
@endphp

@section('headers')
    <th scope="col" class="px-6 py-3">
        #
    </th>
    <th scope="col" class="px-6 py-3">
        Nombre
    </th>
    <th scope="col" class="px-6 py-3">
        Email
    </th>
    <th scope="col" class="px-6 py-3">
        Teléfono
    </th>
    <th scope="col" class="px-6 py-3">
        Acciones
    </th>
@endsection

@section('content-table')
    @foreach($data as $index => $shippingCompany)
        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 {{ !$loop->last ? 'border-b dark:border-gray-700 border-gray-200' : '' }}">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}
            </th>
            <td class="px-6 py-4">
                {{ $shippingCompany->name }}
            </td>
            <td class="px-6 py-4">
                {{ $shippingCompany->email ? $shippingCompany->email : 'Email no registrado' }}
            </td>
            <td class="px-6 py-4">
                {{ $shippingCompany->phones->prefix }} {{ $shippingCompany->phones->number }}
            </td>
            @include('admin.partials.tabla-acctions2', ['item' => $shippingCompany, 'showRoute' => 'admin.shipping-companies.show', 'editRoute' => 'admin.shipping-companies.edit', 'deleteRoute' => 'admin.shipping-companies.destroy'])
        </tr>
    @endforeach
@endsection