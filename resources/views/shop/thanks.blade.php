<x-app-layout>
    <div class="mt-7 mx-auto max-w-96   ">
        @if (session('niubiz'))
            @php
                $response = session('niubiz')['response'];
                $order = session('order');
            @endphp
            <div class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <i class="fa-solid fa-check shrink-0 inline me-3"></i>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">
                        {{ $response['dataMap']['ACTION_DESCRIPTION'] }}
                    </span>
                    <p>
                        <b>Numero de pedido:</b>
                        {{ $response['order']['purchaseNumber'] }}
                    </p>
                    <p>
                        <b>Fecha y hora del pedido:</b>
                        {{ 
                            now()->createFromFormat('ymdHis', $response['dataMap']['TRANSACTION_DATE'])->format('d-m-Y H:i:s')
                        }}
                    </p>
                    <p>
                        <b>Tarjeta:</b>
                        {{ $response['dataMap']['CARD'] }} ({{ $response['dataMap']['BRAND'] }})
                    </p>
                    <p>
                        <b>Importe:</b>
                        {{ $response['order']['amount'] }} {{ $response['order']['currency'] }}
                    </p>
                </div>
                <a href="{{ Storage::url($order->pdf_path) }}" target="_blank" class="underline hover:no-underline">Ver ticket</a>
            </div>
        @endif
        <img class="w-full" src="https://i.pinimg.com/736x/96/99/0e/96990eedfd4494b79adf35d5d1a9044c.jpg" alt="thanks">
    </div>

</x-app-layout>