<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use Illuminate\Support\Facades\Http;
use App\BotMan\Conversations\IAConversation;


class BotManController extends Controller
{
    public function handle(Request $request)
    {
        $config = [];

        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $botman = BotManFactory::create($config);

        // 📋 Opciones
        $botman->hears('consulta', function (BotMan $bot) {
            $question = Question::create("📋 *Selecciona una opción:*")
                ->fallback('No se pudo mostrar las opciones')
                ->callbackId('consulta_options')
                ->addButtons([
                    Button::create('🛒 Ver productos')->value('productos'),
                    Button::create('📍 Ubicación')->value('ubicacion'),
                    Button::create('🕗 Horario')->value('horario'),
                    Button::create('🆘 Ayuda')->value('ayuda'),
                    Button::create('🤖 Asistente IA')->value('asistente_ia'),
                ]);
            $bot->reply($question);
        });

        // 👋 Saludo
        $botman->hears('hola', function (BotMan $bot) {
            $bot->reply("✋ ¡Hola! Soy Petbot, tu asistente virtual. Escribe 'consulta' para ver los disponibles.");
        });

        // 🛒 Mostrar productos
        $botman->hears('productos', function (BotMan $bot) {
            $productos = Product::with('variants')->get();

            if ($productos->isEmpty()) {
                $bot->reply("Lo siento, no hay productos disponibles en este momento.");
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

        // 🕗 Horario
        $botman->hears('horario', function (BotMan $bot) {
            $bot->reply("Nuestro horario de atención es:\n🕗 Lunes a Sábado de 8:00 a.m. a 6:00 p.m.");
            $this->showConsultaButton($bot);
        });

        // 📍 Ubicación
        $botman->hears('ubicacion', function (BotMan $bot) {
            $bot->reply("📍 Nos encontramos en:\nAvenida de las flores 418, Huancayo.");
            $this->showConsultaButton($bot);
        });

        // 🆘 Ayuda
        $botman->hears('ayuda', function (BotMan $bot) {
            $bot->reply("📞 Puedes comunicarte con nosotros al número: *906660509*.");
            $this->showConsultaButton($bot);
        });

        // 🤖 Asistente IA con ask()
        $botman->hears('asistente_ia', function (BotMan $bot) {
            $bot->reply('🤖 ¡Hola! Soy tu asistente IA. Escribe tu pregunta, y trataré de ayudarte.');

            $bot->ask('¿Qué quieres saber?', function (Answer $answer) use ($bot) {
                $pregunta = $answer->getText();
                $respuesta = app(BotManController::class)->responderConIA($pregunta);
                $bot->reply($respuesta);

                // Opcional: volver a mostrar menú
                $question = Question::create("¿Deseas hacer otra consulta?")
                    ->addButtons([
                        Button::create('🔁 Ver opciones')->value('consulta'),
                    ]);
                $bot->reply($question);
            });
        });

        // ❓ Fallback
        $botman->fallback(function (BotMan $bot) {
            $bot->reply("Lo siento, no entendí eso. Escribe 'consulta' para ver opciones.");
        });

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

    public function responderConIA($pregunta)
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
