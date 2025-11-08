<?php

namespace Core;

use Twilio\Rest\Client;

final class Twilio
{
    private Client $twilio;

    public function __construct()
    {
        $this->twilio = new Client(getenv('TWILIO_ACCOUNT_SID'), getenv('TWILIO_AUTH_TOKEN'));
    }

    public function sendSms(string $to, string $text)
    {
        $this->twilio->messages
            ->create(
                $to,
                array(
                    "messagingServiceSid" => getenv('TWILIO_MESSAGING_SERVICE_SID'),
                    "body" => $text
                )
            );
    }
}
