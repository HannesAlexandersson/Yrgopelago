<?php
/*
require __DIR__ . '/../vendor/autoload.php';
 */
/*
use GuzzleHttp\Client;

class CentralBankService
{
    private $startCodeUrl = 'https://www.yrgopelag.se/centralbank/startCode';
    private $transferCodeUrl = 'https://www.yrgopelag.se/centralbank/transferCode';
    private $depositUrl = 'https://www.yrgopelag.se/centralbank/deposit';
    private $client;
    private $islandUrl = 'https://www.yrgopelag.se/centralbank/islands';

    public function __construct()
    {
        $this->client = new Client();
    }
    /*Validating transfercode */
 /*    public function validateTransferCode(string $transferCode, float $totalCost): array
    {

 */
   /*    try {
          // Send a POST request to the central bank API
            $response = $this->client->post($this->transferCodeUrl, [
              "form_params" => [
                "transferCode" => $transferCode,
                "totalCost" => $totalCost
              ]
          ]);
          $body = $response->getBody()->getContents();
      return $body;
      } catch (\Exception $e) {
          return ['error' => $e->getMessage()];
      }
    } */

    // Deposit transfer code function
  /*   public function depositTransferCode(string $transfercode): array
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
        return $body;
      } else {
          return null;
      }

    } */
/*
    public function getIslands(): void
    {
      try {
        $response = $this->client->request('GET', 'https://www.yrgopelag.se/centralbank/islands', [
          'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
          ]
        ]);

        $body = $response->getBody()->getContents();
        echo "Response: " . $body;
      } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    }
 */
/*
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
}
  */