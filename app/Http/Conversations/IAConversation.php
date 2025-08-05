<?php

namespace App\Http\Controllers\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Http;

class IAConversation extends Conversation
{
    public function askQuestion()
    {
        $this->ask('🤖 ¿Qué deseas saber?', function (Answer $answer) {
            $pregunta = $answer->getText();
            $respuesta = $this->consultarIAAPI($pregunta);
            $this->say($respuesta);
            $this->askAnother();
        });
    }

    public function askAnother()
    {
        $this->ask('¿Deseas hacer otra consulta?', function (Answer $answer) {
            $texto = strtolower($answer->getText());
            if (in_array($texto, ['sí', 'si', 'claro', 'sí por favor', 'ok'])) {
                $this->askQuestion();
            } else {
                $this->say('Gracias por usar el asistente IA. 👋');
            }
        });
    }

    public function run()
    {
        $this->askQuestion();
    }

    private function consultarIAAPI($pregunta)
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
            return '⚠️ No se pudo obtener una respuesta de la IA.';
        }

        return $response['choices'][0]['message']['content'] ?? '❌ No se obtuvo una respuesta válida.';
    }
}
