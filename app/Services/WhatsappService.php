<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WhatsappService
{
    /**
     * Envía un mensaje de texto simple por WhatsApp usando la API configurada.
     *
     * @param string      $to       Número de destino.
     * @param string      $message  Contenido del mensaje.
     * @param string|null $template Nombre de plantilla, si aplica en la API externa.
     *
     * @return array Respuesta en JSON (si la API la devuelve).
     */
    public function sendMessage(string $to, string $message, ?string $template = null): array
    {
        $endpoint = rtrim((string)config('services.whatsapp.endpoint'), '/');
        $token    = config('services.whatsapp.token');
        $from     = config('services.whatsapp.from');

        if (empty($endpoint) || empty($token)) {
            throw new RuntimeException('Configuración de WhatsApp incompleta. Revisa tu .env.');
        }

        $payload = [
            'to'      => $this->normalizePhone($to),
            'message' => $message,
        ];

        if (!empty($from)) {
            $payload['from'] = $from;
        }

        if (!empty($template)) {
            $payload['template'] = $template;
        }

        $response = Http::withToken($token)->post($endpoint, $payload);

        if ($response->failed()) {
            Log::error('whatsapp_api_error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            throw new RuntimeException('Error al comunicarse con la API de WhatsApp.');
        }

        return $response->json() ?? [];
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if (empty($digits)) {
            throw new RuntimeException('El celular del postulante no es válido.');
        }

        // Añade +51 si vienen 9 dígitos (Perú). Ajusta según tu mercado.
        if (strlen($digits) === 9) {
            $digits = '51' . $digits;
        }

        if (!str_starts_with($digits, '+')) {
            $digits = '+' . $digits;
        }

        return $digits;
    }
}
