<?php

namespace Core;

use Mailgun\Mailgun;

final class MailgunClient
{
    private Mailgun $mg;

    public function __construct()
    {
        $this->mg = Mailgun::create(getenv('MAILGUN_API_KEY'));
    }

    public function sendEmail(string $to, string $subject, ?string $text = null, ?string $html = null)
    {
        $this->mg->messages()->send(
            getenv('MAILGUN_DOMAIN'),
            [
                'from' => 'Mailgun Sandbox <postmaster@' . getenv('MAILGUN_DOMAIN') . '>',
                'to' => $to,
                'subject' => $subject,
                'text' => $text ?? '',
                'html' => $html
            ]
        );
    }
}
