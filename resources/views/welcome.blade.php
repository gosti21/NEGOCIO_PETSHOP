<x-app-layout>

    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <style>
            .swiper-pagination-bullet {
                background-color: #FEC51C;
                width: 12px;
                height: 12px;
            }

            /* 💬 Estilos chatbot */
            #chatbot {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 320px;
                background: white;
                border: 1px solid #ddd;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                flex-direction: column;
                overflow: hidden;
                z-index: 50;
            }
            #chatbot-header {
                background: #fec51c;
                padding: 8px 12px;
                font-weight: bold;
                color: #222;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            #chatbot-messages {
                flex: 1;
                padding: 10px;
                overflow-y: auto;
                font-size: 14px;
                background: #f9f9f9;
            }
            #chatbot-footer {
                display: flex;
                border-top: 1px solid #ddd;
            }
            #chatbot-input {
                flex: 1;
                padding: 8px;
                border: none;
                outline: none;
            }
            #chatbot-send, #chatbot-reset {
                background: #fec51c;
                border: none;
                padding: 8px 12px;
                cursor: pointer;
                font-weight: bold;
            }
        </style>
    @endpush

    <!-- Slider main container -->
    <div class="swiper mb-10">
        <div class="swiper-wrapper">
            @foreach ($covers as $cover)
                <div class="swiper-slide">
                    @if ($cover->images->first())
                        <img src="{{ Storage::url($cover->images->first()->path) }}"
                            class="w-full aspect-[3/1] object-cover object-center" alt="">
                    @else
                        <img src="{{ Storage::url($cover->images->first()->path) }}"
                            class="w-full aspect-[3/1] object-cover object-center" alt="img-default-cover">
                    @endif
                </div>
            @endforeach
        </div>

        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev text-[#FEC51C] font-semibold"></div>
        <div class="swiper-button-next text-[#FEC51C] font-semibold"></div>
    </div>

    <x-container>
        <h1 class="text-2xl font-bold text-gray-700 dark:text-white mb-4 mx-6 sm:mx-0 md:mx-0 lg:mx-0">
            Últimos productos
        </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mx-6 sm:mx-0 md:mx-0 lg:mx-0">
            @foreach ($lastProducts as $product)
                @if (count($product->variants))
                    <article
                        class="bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700 shadow rounded overflow-hidden relative group">
                        <a href="{{ route('products.show', $product) }}">
                            @php
                                $variant = $product->variants->first();
                                $image = $variant?->images->first();
                            @endphp

                            @if ($image)
                                <img src="{{ Storage::url($image->path) }}" alt="img-product-{{ $product->name }}"
                                    class="w-full h-48 object-cover object-center">
                            @else
                                <img src="{{ asset('storage/default-product.jpg') }}" alt="img-default"
                                    class="w-full h-48 object-cover object-center">
                            @endif

                            <div class="p-4">
                                <h1
                                    class="text-lg font-bold text-gray-900 dark:text-white mb-1 line-clamp-2 min-h-[56px]">
                                    {{ $product->name }}
                                </h1>

                                <p class="text-gray-900 dark:text-gray-200 mb-4">
                                    S/. {{ number_format($variant?->price ?? 0, 2) }}
                                </p>

                                <span
                                    class="btn btn-blue block w-full text-center opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity duration-300">
                                    Ver producto
                                </span>
                            </div>
                        </a>
                    </article>
                @endif
            @endforeach
        </div>
    </x-container>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            const swiper = new Swiper('.swiper', {
                loop: true,
                autoplay: {
                    delay: 6000,
                },
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        </script>
    @endpush

    <!-- 💬 Chatbot personalizado -->
    <div id="chatbot">
        <div id="chatbot-header">
            Petbot 🐾
            <button id="chatbot-reset">↺</button>
        </div>
        <div id="chatbot-messages"></div>
        <div id="chatbot-footer">
            <input id="chatbot-input" type="text" placeholder="Escribe tu mensaje...">
            <button id="chatbot-send">Enviar</button>
        </div>
    </div>

    <script>
        function setWelcomeMessage() {
            let messagesDiv = document.getElementById('chatbot-messages');
            messagesDiv.innerHTML = `
                <div class="text-left">
                    <span class="bg-gray-200 dark:bg-gray-800 px-2 py-1 rounded-lg">
                        👋 Hola, soy tu asistente virtual 🐾 ¿Qué productos nuevos veremos hoy para tu mascota?
                    </span>
                </div>
            `;
        }

        document.addEventListener("DOMContentLoaded", setWelcomeMessage);
        document.getElementById('chatbot-reset').addEventListener('click', setWelcomeMessage);

        document.getElementById('chatbot-send').addEventListener('click', sendMessage);
        document.getElementById('chatbot-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });

        function formatBotReply(text) {
            let formatted = text.replace(/\n/g, '<br>');
            if (formatted.includes("-")) {
                let lines = formatted.split(/<br>/g);
                let inList = false;
                let final = "";
                lines.forEach(line => {
                    if (line.trim().startsWith("-")) {
                        if (!inList) {
                            final += "<ul class='list-disc list-inside space-y-1'>";
                            inList = true;
                        }
                        final += "<li>" + line.replace(/^-+/, '').trim() + "</li>";
                    } else {
                        if (inList) {
                            final += "</ul>";
                            inList = false;
                        }
                        final += line + "<br>";
                    }
                });
                if (inList) final += "</ul>";
                formatted = final;
            }
            return formatted;
        }

        function sendMessage() {
            let input = document.getElementById('chatbot-input');
            let message = input.value.trim();
            if (!message) return;

            let messagesDiv = document.getElementById('chatbot-messages');
            messagesDiv.innerHTML +=
                `<div class="text-right"><span class="bg-yellow-300 px-2 py-1 rounded-lg">${message}</span></div>`;
            input.value = '';

            fetch("{{ route('chatbot.ask') }}", {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.text().then(text => { throw new Error(text) });
                    }
                    return res.json();
                })
                .then(data => {
                    let formattedReply = formatBotReply(data.reply);
                    messagesDiv.innerHTML +=
                        `<div class="text-left"><span class="bg-gray-200 dark:bg-gray-800 px-2 py-1 rounded-lg block">${formattedReply}</span></div>`;
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                })
                .catch(err => {
                    console.error("Error del chatbot:", err);
                    messagesDiv.innerHTML +=
                        `<div class="text-left text-red-500"><span>Error en el servidor</span></div>`;
                });
        }
    </script>

</x-app-layout>
