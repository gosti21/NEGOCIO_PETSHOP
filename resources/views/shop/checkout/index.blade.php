<x-app-layout>
    <div class="text-gray-800 dark:text-gray-200 mt-7" x-data="{
        pago: 1
    }">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="col-span-1 bg-white dark:bg-gray-800">
                <div class="lg:max-w-[45rem] py-10 px-4 lg:pr-8 sm:pl-6 lg:pl-8 ml-auto">
                    <h1 class="text-2xl mb-2 font-medium">
                        Pago
                    </h1>
                    <div class="shadow rounded-lg overflow-hidden border border-gray-600 dark:border-gray-300">
                        <ul class="divide-y divide-gray-800 dark:divide-gray-500">
                            <li>
                                <label class="p-4 flex items-center">
                                    <x-radio value="1" x-model="pago"></x-radio>
                                    <span class="ml-2">
                                        Tarjeta de debito / crédito
                                    </span>

                                    <img class="h-6 ml-auto" src="https://codersfree.com/img/payments/credit-cards.png"
                                        alt="">
                                </label>

                                <div class="p-4 bg-gray-100 dark:bg-gray-700 text-center border-t border-gray-600 dark:border-gray-300"
                                    x-show="pago == 1">
                                    <i class="fa-solid fa-credit-card text-9xl"></i>
                                    <p class="mt-2">
                                        Luego de hacer click al "Pagar ahora", se abrira mercado pago para
                                        completar tu compra de forma segura.
                                    </p>
                                </div>
                            </li>

                            <li>
                                <label class="p-4 flex items-center">
                                    <x-radio value="2" x-model="pago"></x-radio>
                                    <span class="ml-2">
                                        Depósito Bancario o Yape
                                    </span>
                                </label>
                                <div class="p-4 bg-gray-100 dark:bg-gray-700 flex justify-center border-t border-gray-600 dark:border-gray-300"
                                    x-cloak x-show="pago == 2">
                                    <div>
                                        <p>1. Pago por depósito o transferencia bancaria:</p>
                                        <p>- BCP soles: 451-123456789-89</p>
                                        <p>- CCI: 001-456-897159423</p>
                                        <p>- Razón social: </p>
                                        <p>- RUC: 134859848</p>
                                        <p>1. Pago por Yape:</p>
                                        <p>- Yapea al número: 9856123456 ()</p>
                                        <p>
                                            Enviar el comprobante de pago a 9856123456
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="lg:max-w-[45rem] py-12 px-4 lg:pl-8 sm:pr-6 lg:pr-8 mr-auto">
                    <ul class="space-y-4 mb-4">
                        @foreach (Cart::instance('shopping')->content() as $item)
                            <li class="flex items-center space-x-4">
                                <div class="flex-shrink-0 relative">
                                    <img class="h-14 aspect-square" src="{{ Storage::url($item->options->image) }}"
                                        alt="">
                                    <div
                                        class="flex justify-center items-center h-6 w-6 bg-gray-900 dark:bg-gray-200 bg-opacity-70 rounded-full absolute -right-2 -top-2">
                                        <span class="font-semibold dark:text-gray-700">
                                            {{ $item->qty }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    {{ $item->name }}
                                </div>
                                <div class="flex-shrink-0">
                                    <p>
                                        S/. {{ $item->price }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="flex justify-between">
                        <p>
                            SubTotal
                        </p>
                        <p>
                            S/. {{ Cart::instance('shopping')->subtotal() }}
                        </p>
                    </div>

                    <div class="flex justify-between">
                        <p>
                            Precio de envío
                            <i class="fas fa-info-circle ml-1" title="El precio del envío es de S/. 15.00"></i>
                        </p>
                        <p>
                            S/. 15.00
                        </p>
                    </div>

                    <hr class="my-4">

                    <div class="flex justify-between mb-4">
                        <p class="text-lg font-semibold">
                            Total
                        </p>
                        <p>
                            S/. {{ Cart::instance('shopping')->subtotal() + 15 }}
                        </p>
                    </div>
                    <div>
                        <div id="wallet_container"></div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://sdk.mercadopago.com/js/v2"></script>

        <script>
            const mp = new MercadoPago("{{ config('services.mercadopago.public_key') }}", {
                locale: "es-PE"
            });

            mp.checkout({
                preference: {
                    id: "{{ $preferenceId }}"
                },
                render: {
                    container: "#wallet_container",
                    label: "Pagar ahora con Mercado Pago"
                }
            });
        </script>
    @endpush
</x-app-layout>
