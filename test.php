<?php

require 'CentralBankService.php';

$startcode = 'your-startcode'; // Replace with your actual startcode

$centralBankService = new CentralBankService();
$api_key = $centralBankService->getApiKey($startcode);

if ($api_key) {
    // Save API key to a file
    file_put_contents('api_key.txt', $api_key);
    echo "API Key: $api_key saved successfully.";
} else {
    echo "Error getting API Key";
}