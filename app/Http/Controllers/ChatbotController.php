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
                'content' => 'Eres un asistente virtual especializado en una tienda de mascotas.'
            ];
            $messages[] = [
                'role' => 'assistant',
                'content' => '🐾 ¡Hola! Bienvenido a la tienda de mascotas. ¿En qué puedo ayudarte hoy?'
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
