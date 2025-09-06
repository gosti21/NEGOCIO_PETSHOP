<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Obtenemos historial desde sesión
        $messages = session('chat_history', []);


        // Si es la primera vez, agregamos el system
        if (empty($messages)) {
            $messages[] = [
                'role' => 'system',
                'content' => 'Eres un asistente virtual amigable y experto en productos de una tienda de mascotas.
Tu misión es ayudar a los clientes con información sobre alimento, juguetes, accesorios, salud, higiene y cuidados de mascotas.
Instrucciones importantes:
1. Responde siempre de forma breve (máx. 4 frases).
2. Organiza tus respuestas en viñetas o pasos si hay varias opciones.
3. Usa un tono cercano, positivo y emojis relacionados 🐾🐶🐱.
4. No respondas temas fuera de productos de mascotas.
   - Si ocurre, redirige con algo breve como:
   "🐾 Lo entiendo, pero mi especialidad son productos para mascotas. ¿Quieres ver opciones para tu engreído?"'
            ];
            $messages[] = [
                'role' => 'assistant',
                'content' => '🐾 ¡Hola! Bienvenido a la tienda de mascotas. ¿Buscas alimento, juguetes o algún accesorio especial hoy?'
            ];
        }


        // Agregamos el mensaje del usuario
        $messages[] = ['role' => 'user', 'content' => $request->message];

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $messages,
            ]);

            $reply = $response->choices[0]->message->content ?? "No recibí respuesta 😿";

            // Guardamos respuesta en historial
            $messages[] = ['role' => 'assistant', 'content' => $reply];
            session(['chat_history' => $messages]);

            return response()->json(['reply' => $reply]);
        } catch (\Exception $e) {
            return response()->json([
                'reply' => "⚠️ Error en el servidor: " . $e->getMessage()
            ], 500);
        }
    }

    public function reset()
    {
        session()->forget('chat_history');
        return response()->json(['reply' => '🐾 ¡Conversación reiniciada!']);
    }
}
