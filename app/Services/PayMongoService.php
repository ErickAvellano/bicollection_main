<?php

namespace App\Services;

use GuzzleHttp\Client;

class PayMongoService
{
    protected $client;
    protected $secretKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->secretKey = config('services.paymongo.secret_key');
    }

    // Create GCash Source
    public function createGcashSource($amount)
    {
        $url = config('services.paymongo.base_url') . '/sources';

        try {
            $response = $this->client->post($url, [
                'auth' => [$this->secretKey, ''],
                'json' => [
                    'data' => [
                        'attributes' => [
                            'amount' => $amount * 100, // Convert to centavos
                            'type' => 'gcash',
                            'currency' => 'PHP',
                            'redirect' => [
                                'success' => route('payment.success'),
                                'failed' => route('payment.failed'),
                            ]
                        ]
                    ]
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return null;
        }
    }
}
