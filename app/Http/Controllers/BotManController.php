<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Conversations\IAConversation;

class BotManController extends Controller
{
    public function handle(Request $request)
    {
        $config = [];

        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $botman = BotManFactory::create($config);

        $botman->hears('hola', function (BotMan $bot) {
            $bot->reply("✋ ¡Hola! Soy Petbot, tu asistente virtual. Escribe 'consulta' para ver las opciones disponibles.");
            $this->showConsultaButton($bot);
        });

        // 📋 Menú principal
        $botman->hears('consulta', function (BotMan $bot) {
            $question = Question::create("📋 *Selecciona una opción:*")
                ->fallback('No se pudo mostrar las opciones')
                ->callbackId('consulta_options')
                ->addButtons([
                    Button::create('🛒 Ver productos')->value('ver_productos'),
                    Button::create('📍 Ubicación')->value('ubicacion'),
                    Button::create('🕗 Horario')->value('horario'),
                    Button::create('🎉 Promociones')->value('promociones'),
                    Button::create('📞 Contacto')->value('contacto'),
                    Button::create('🤖 Asistente IA')->value('asistente_ia'),
                ]);
            $bot->reply($question);
        });

        // 🛒 Ver productos
        $botman->hears('ver_productos', function (BotMan $bot) {
            $productos = Product::with('variants')->get();

            if ($productos->isEmpty()) {
                $bot->reply("🚫 No hay productos disponibles en este momento.");
            } else {
                $msg = "🛒 *Nuestros productos disponibles:*\n\n";
                foreach ($productos as $index => $producto) {
                    $variant = $producto->variants->first();
                    $precio = $variant ? number_format($variant->price, 2) : '0.00';
                    $msg .= "🔹 *" . ($index + 1) . ". {$producto->name}*\n";
                    $msg .= "   💵 Precio: S/. {$precio}\n";
                    $msg .= "-------------------------------------\n";
                }
                $bot->reply($msg);
            }

            $this->showConsultaButton($bot);
        });

        // 📍 Ubicación
        $botman->hears('ubicacion', function (BotMan $bot) {
            $bot->reply("📍 Nos encontramos en:\nAvenida de las flores 418, Huancayo.");
            $this->showConsultaButton($bot);
        });

        // 🕗 Horario
        $botman->hears('horario', function (BotMan $bot) {
            $bot->reply("🕗 Nuestro horario de atención es:\nLunes a Sábado de 8:00 a.m. a 6:00 p.m.");
            $this->showConsultaButton($bot);
        });

        // 🎉 Promociones
        $botman->hears('promociones', function (BotMan $bot) {
            $bot->reply("🎉 *Promociones actuales:*\n- 20% de descuento en alimento para gatos\n- Combo de shampoo + cepillo a solo S/. 35.00\n¡Válido hasta el sábado!");
            $this->showConsultaButton($bot);
        });

        // 📞 Contacto
        $botman->hears('contacto', function (BotMan $bot) {
            $bot->reply("📞 Puedes comunicarte con nosotros por WhatsApp al siguiente enlace:\n👉 https://wa.me/51906660509");
            $this->showConsultaButton($bot);
        });

        // 🤖 Asistente IA (aquí corriges el botón)
        $botman->hears('asistente_ia', function (BotMan $bot) {
            $bot->startConversation(new IAConversation());
        });

        // 🛑 Fallback
        $botman->fallback(function (BotMan $bot) {
            $bot->reply("Lo siento, no entendí eso. Escribe 'consulta' para ver las opciones.");
            $this->showConsultaButton($bot);
        });

        // 🚀 Ejecutar bot
        $botman->listen();
    }

    private function showConsultaButton(BotMan $bot)
    {
        $question = Question::create("¿Deseas hacer otra consulta?")
            ->addButtons([
                Button::create('🔁 Ver opciones')->value('consulta'),
            ]);
        $bot->reply($question);
    }

    // (opcional) respuesta directa sin conversación
    public function responderConIATexto($pregunta)
    {
        $apiKey = env('OPENAI_API_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $pregunta],
            ],
        ]);

        if ($response->failed()) {
            return '⚠️ Error al conectar con OpenAI.';
        }

        return $response['choices'][0]['message']['content'] ?? '❌ No pude generar una respuesta.';
    }
}
