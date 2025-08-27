<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
    ],
]">
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 text-gray-900 dark:text-white">
        <!-- Bienvenida -->
        <div class="rounded-2xl shadow-md p-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-4">
                <img class="w-14 h-14 rounded-full object-cover ring-2 ring-[#FEC51C]"
                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
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
        <div
            class="rounded-2xl shadow-md p-6 bg-gradient-to-br from-yellow-300 to-yellow-500 dark:from-[#FEC51C] dark:to-yellow-600 text-center text-black dark:text-white flex items-center justify-center">
            <div>
                <h2 class="text-2xl font-bold">Panel de Administración</h2>
                <p class="text-sm mt-2">
                    Gestiona familias, categorías, productos, usuarios y más.
                </p>
            </div>
        </div>
    </section>

    <!-- Chatbot -->
    <div id="chatbot"
        class="fixed bottom-4 right-4 w-80 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl shadow-lg">
        <div class="p-3 bg-yellow-400 text-black font-bold rounded-t-2xl">
            🤖 Chatbot
        </div>
        <div id="chatbot-messages" class="p-3 h-64 overflow-y-auto text-sm space-y-2"></div>
        <div class="flex border-t border-gray-300 dark:border-gray-700">
            <input id="chatbot-input" type="text" placeholder="Escribe tu mensaje..."
                class="flex-1 p-2 text-gray-900 dark:text-white bg-transparent border-0 focus:ring-0" />
            <button id="chatbot-send" class="p-2 bg-yellow-400 hover:bg-yellow-500 text-black font-bold rounded-br-2xl">
                ➤
            </button>
        </div>
    </div>

    <script>
        document.getElementById('chatbot-send').addEventListener('click', sendMessage);
        document.getElementById('chatbot-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });

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
                    body: JSON.stringify({
                        message
                    })
                })
                .then(res => res.json())
                .then(data => {
                    messagesDiv.innerHTML +=
                        `<div class="text-left"><span class="bg-gray-200 dark:bg-gray-800 px-2 py-1 rounded-lg">${data.reply}</span></div>`;
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                });
        }
    </script>


</x-admin-layout>
