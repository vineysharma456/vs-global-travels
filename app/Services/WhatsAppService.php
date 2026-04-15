<?php

namespace App\Services;

use Twilio\Rest\Client;

class WhatsAppService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(
            env('TWILIO_SID'),
            env('TWILIO_AUTH_TOKEN')
        );
    }

    // ✅ Template message (first message)
    public function sendTemplate($to, $variables = [])
    {
        return $this->client->messages->create(
            'whatsapp:' . $to,
            [
                'from' => env('TWILIO_WHATSAPP_FROM'),
                'contentSid' => 'HXb5b62575e6e4ff6129ad7c8efe1f983e',
                'contentVariables' => json_encode($variables)
            ]
        );
    }

    // ✅ Normal message (after reply)
    public function sendMessage($to, $message)
    {
        return $this->client->messages->create(
            'whatsapp:' . $to,
            [
                'from' => env('TWILIO_WHATSAPP_FROM'),
                'body' => $message
            ]
        );
    }
}