<?php

namespace App\Services;

use Twilio\Rest\Client;

class SmsService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function send($to, $message)
    {
        return $this->client->messages->create($to, [
            'from' => config('services.twilio.from'),
            'body' => $message,
        ]);
    }
} 