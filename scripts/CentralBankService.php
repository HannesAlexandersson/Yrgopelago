<?php

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

class CentralBankService
{
    private $startCodeUrl = 'https://www.yrgopelag.se/centralbank/startCode';
    private $transferCodeUrl = 'https://www.yrgopelag.se/centralbank/transferCode';
    private $depositUrl = 'https://www.yrgopelag.se/centralbank/deposit';
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getApiKey($startcode)
    {
        $response = $this->client->post($this->startCodeUrl, [
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

    /*Validating transfercode */
    public function validateTransferCode(string $transfercode, float $totalCost): array
    {
    /*   $transfercodeEndpoint = $baseUrl . '/transferCode'; */
     /*  $this->client = new Client(); */
      try {
          // Send a POST request to the central bank
          $response = $this->client->post($this->transferCodeUrl, [
              'form_params' => [
                'transferCode' => $transfercode,
                'totalCost' => $totalCost
              ]
          ]);
          if ($response->getStatusCode() === 200) {
            $responseBody = json_decode($response->getBody(), true);
            header('Content-Type: application/json');
            echo json_encode($responseBody);
            file_put_contents('transfercode.json', json_encode($responseBody));
            return $responseBody;
          } else {
            return ['error' => 'Could not validate transfer code'];
            exit();
          }
      } catch (\Exception $e) {
          return ['error' => $e->getMessage()];
      }
    }

    // Deposit transfer code function
    public function depositTransferCode(string $transfercode): array
    {
      $user = 'Hannes';
      $response = $this->client->post($this->depositUrl, [
          'form_params' => [
            'user' => $user,
            'transferCode' => $transfercode
          ]
      ]);

      if ($response->getStatusCode() === 200) {
        $body = json_decode($response->getBody(), true);
        echo 'Transfer code deposited successfully';
        return $body;
      } else {
          echo 'Could not deposit transfer code';
          return null;
      }

    }
}

