<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IAController extends Controller
{
    /**
     * Recibe una pregunta y responde con la IA de OpenAI.
     */
    public function responderConIA(Request $request)
    {
        $pregunta = $request->input('pregunta', '¿Cuál es la capital de Francia?');

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

        return response()->json([
            'respuesta' => $response['choices'][0]['message']['content'] ?? 'No pude generar una respuesta.'
        ]);
    }
}

