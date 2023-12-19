<?php
use GuzzleHttp\Client;
require __DIR__ . '/../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
$transferCode = 'bcf510ac-4086-4e8b-bd33-b7af34c93fd1';
$totalCost = 20;


  $client = new GuzzleHttp\Client();
    $validate = $client->request('POST', 'https://www.yrgopelag.se/centralbank/transferCode', ['form_params' => [
         'transferCode' => "bcf510ac-4086-4e8b-bd33-b7af34c93fd1",
         'totalcost' => 20
    ]]);
    $body = $validate->getBody()->getContents();
    echo "Response: " . $body;

/*
  $response = $this->client->post($this->transferCodeUrl, [
              "form_params" => [
                "transferCode" => $transferCode,
                "totalCost" => $totalCost
              ]
          ]);
          $body = json_decode($response->getBody(), true);
      echo "Response: " . $body;





$url = 'https://www.yrgopelag.se/centralbank/transferCode';
$transferCode = 'bcf510ac-4086-4e8b-bd33-b7af34c93fd1';
$totalCost = 20;

$data = [
    'transferCode' => $transferCode,
    'totalCost' => $totalCost,
];

$options = [
    CURLOPT_URL => $url,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => http_build_query($data),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => true,
];

$ch = curl_init();
curl_setopt_array($ch, $options);

$response = curl_exec($ch);

if ($response === false) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    echo 'cURL response: ' . $response;
}

curl_close($ch);
 */




/* $url = 'https://www.yrgopelag.se/centralbank/transferCode';

$data = [
    'transferCode' => $transferCode,
    'totalCost' => $totalCost,
];

$options = [
    'http' => [
        'follow_location' => 1,
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => http_build_query($data),
    ],
];

$context = stream_context_create($options);
$body = file_get_contents($url, false, $context);

// Handle the response
echo $body;
var_dump($body);
 */



/*
$client = new GuzzleHttp\Client();

try {
  $response = $client->request('POST', 'https://www.yrgopelag.se/centralbank/transferCode', [
    "form_params" => [
        "transferCode" => "bcf510ac-4086-4e8b-bd33-b7af34c93fd1",
        "totalCost" => 20
    ],
    "headers" => [
        "Content-Type" => "application/x-www-form-urlencoded"
    ]
]);

  $body = $response->getBody()->getContents();
  echo "Response: " . $body;
} catch (\Exception $e) {
  echo "Error: " . $e->getMessage();
}
*/
