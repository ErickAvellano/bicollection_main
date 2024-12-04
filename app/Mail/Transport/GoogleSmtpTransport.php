<?php

namespace App\Mail\Transport;

use Google_Client;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Envelope;

class GoogleSmtpTransport extends EsmtpTransport
{
    protected $client;

    public function __construct(Google_Client $client)
    {
        parent::__construct('smtp.gmail.com', 587);
        $this->client = $client;
    }

    protected function doSend(SentMessage $message): void
    {
        $accessToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
        $this->setUsername(env('MAIL_USERNAME'));
        $this->setPassword($accessToken['access_token']);
        parent::doSend($message);
    }
}
