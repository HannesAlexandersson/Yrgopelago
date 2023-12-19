<?php

require __DIR__ . '/../vendor/autoload.php';
use GuzzleHttp\Client;


// validate the transfer code and the amount on the transfer code
function validateTransferCode(string $transferCode, float $totalCost): array
{
  try{
    $validateClient = new GuzzleHttp\Client();
    $validateResponse = $validateClient->request('POST', 'https://www.yrgopelag.se/centralbank/transferCode', ['form_params' => [
        'transferCode' => $transferCode,
        'totalcost' => $totalCost
    ]]);
    $bankResponseValidation = json_decode($validateResponse->getBody()->getContents(), true);
    file_put_contents('validation_response.json', json_encode($bankResponseValidation), FILE_APPEND);
    return $bankResponseValidation;
  }
  catch (\Exception $e) {
      return ['error' => $e->getMessage()];
  }
}
// Deposit the transfer code into my account
function depositTransferCode(string $transferCode, string $hotelManager ): array
{
  try{
    // Deposit the transfer code
    $depositClient = new GuzzleHttp\Client();
    $depositResponse = $depositClient->request('POST', 'https://www.yrgopelag.se/centralbank/deposit', ['form_params' => [
        'user' => $hotelManager,
        'transferCode' => $transferCode
    ]]);
    $bankResponseDeposit = json_decode($depositResponse->getBody()->getContents(), true);
    file_put_contents('deposit_response.json', json_encode($bankResponseDeposit), FILE_APPEND);
    return $bankResponseDeposit;
  } catch (\Exception $e) {
      return ['error' => $e->getMessage()];
  }
}