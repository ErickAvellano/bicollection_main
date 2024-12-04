<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Client;
use Illuminate\Mail\MailManager;
use App\Mail\Transport\GoogleSmtpTransport;

class GoogleMailServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Google_Client::class, function () {
            $client = new Google_Client();
            $client->setClientId(env('GOOGLE_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            $client->refreshToken(env('GOOGLE_REFRESH_TOKEN'));

            return $client;
        });
    }

    public function boot()
    {
        $this->app->resolving(MailManager::class, function (MailManager $mailManager) {
            $mailManager->extend('gmail', function ($config) {
                $client = app(Google_Client::class);
                return new GoogleSmtpTransport($client);
            });
        });
    }
}
