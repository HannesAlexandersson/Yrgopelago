<?php
declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';
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
    $fileContent = file_get_contents('validation_response.json');// get the JSON file with my "guests"
    $data = json_decode($fileContent, true);
    // If the "guests" key doesn't exist or is not an array, initialize it as an empty array(IE if the guest is the very first guest). This is from the file "validation_response.json" wich is a place I store all succeful bookings for the admin page
    if (!isset($data['guests']) || !is_array($data['guests'])) {
      $data['guests'] = [];
    }
    $data['guests'][] = $bankResponseValidation;// add the response to the array
    $newContent = json_encode($data, JSON_PRETTY_PRINT);// encode the array as json
    file_put_contents('validation_response.json', $newContent);// save the response as json in a separate file for debugging reasons, and for the adminpage

    return $bankResponseValidation; // return the response to the "handleBooking.php" script
  }
  catch (\Exception $e) { // catch any errors and log them
      return ['error' => $e->getMessage()];
  }
}
// Deposit the transfer code into my account, This is done automatic when the booking is made and the validation of the transfercode is OK
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
    file_put_contents('deposit_response.json', json_encode($bankResponseDeposit), FILE_APPEND);//save the response as json in a separate file for debugging reasons
    return $bankResponseDeposit;
  } catch (\Exception $e) {
      return ['error' => $e->getMessage()];
  }
}