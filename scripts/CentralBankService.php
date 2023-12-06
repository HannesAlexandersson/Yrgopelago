<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

class CentralBankService
{
    private $url = 'https://www.yrgopelag.se/centralbank/startCode';
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getApiKey($startcode)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'startcode' => $startcode,
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            $body = json_decode($response->getBody(), true);
            return $body['api_key'];
        } else {
            // Handle the error or provide feedback
            return null;
        }
    }
}