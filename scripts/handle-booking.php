<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/CentralBankService.php';
require __DIR__ . '/database-communications.php';
use Dotenv\Dotenv;
use GuzzleHttp\Client;
// Load the environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Access the values from dotenv
$islandName = $_ENV['ISLAND_NAME'];
$hotelName = $_ENV['HOTEL_NAME'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Parse JSON data from the JavaScript request
  $postData = json_decode(file_get_contents("php://input"), true);


// Check if the JSON data is valid
if ($postData === null) {
  // Respond with an error if the JSON data is invalid
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Invalid JSON data']);
  exit();
}

// Retrieve form data
$room_id = isset($postData["room"]) ? (int) $postData["room"] : 0;
$room;
// Validate room, I need the actual room name for the response aswell for visual purposes
if($room_id == 1){
  $room = 'The Gaze';
}else if($room_id == 2){
  $room = 'The Tranquility';
}else if($room_id == 3){
  $room = 'The Presidential';
};
//validate the dates
$arrivalDate = isset($postData["arrivalDate"]) ? filter_var($postData["arrivalDate"], FILTER_SANITIZE_STRING) : "";
$departureDate = isset($postData["departureDate"]) ? filter_var($postData["departureDate"], FILTER_SANITIZE_STRING) : "";
//sanitize the user_id
$user_id = isset($postData["user_id"]) ? htmlspecialchars($postData["user_id"]) : "";


$selectedFeaturesNames = []; // I need the actual feature names for the response aswell for visual purposes
$selectedFeatureIDs = []; // but for the bookinglogic i need the feature ids
// Check if features are selected
$selectedFeatureIDs = isset($postData["features"]) && $postData["features"] !== null ? array_map('intval', $postData["features"]) : [];
foreach($selectedFeatureIDs as $feature){
  if($feature == 1){
    $feature1['name'] = 'Massage Therapy';
    array_push($selectedFeaturesNames, $feature1);
    }else if($feature == 2){
    $feature2['name'] = 'Bedtime Storyteller';
    array_push($selectedFeaturesNames, $feature2);
    }else if($feature == 3){
    $feature3['name'] = 'Underground Hotsprings';
    array_push($selectedFeaturesNames, $feature3);
    }
  }
  $availableFeatures = [
    [
      'name' => 'Massage Therapy',
      'cost' => 5,
      'id' => 1
    ],
    [
      'name' => 'Bedtime Storyteller',
      'cost' => 5,
      'id' => 2
    ],
    [
      'name' => 'Underground Hotsprings',
      'cost' => 5,
      'id' => 3
    ]
  ];
  foreach($selectedFeaturesNames as &$selectedFeature){
    foreach($availableFeatures as $availableFeature){
      if($selectedFeature['name'] == $availableFeature['name']){
        $selectedFeature['cost'] = $availableFeature['cost'];
        $selectedFeature['id'] = $availableFeature['id'];
      }
    }
  }
  unset($selectedFeature);




// calculate toatal number fo days the user stays at the hotel
$numberOfDays = calculateDays($arrivalDate, $departureDate);

// Calculate cost
$totalCost = calculateCost($room_id, $selectedFeatureIDs, $numberOfDays);




// Validate TRANSFERCODE check if code is unused and if the user has enough money on the code
  $response = [];
  $centralBankService = new CentralBankService();
  $response = $centralBankService->validateTransferCode($user_id, $totalCost);


  // Perform the booking and charge the user
  $bookingResult = bookRoom($user_id, $room_id, $arrivalDate, $departureDate, $totalCost, $selectedFeatureIDs);
  // Check the result of the booking and send the response as json
  checkBooking($bookingResult, $arrivalDate, $departureDate, $numberOfDays, $totalCost, $selectedFeaturesNames);
  exit();
}

// Function to check the result of the booking
function checkBooking(bool $bookingResult, string $arrivalDate, string $departureDate, int $numberOfDays, float $totalCost, array $selectedFeaturesNames): void
{

    // Check the result of the booking
    if ($bookingResult) {
        $islandName = $_ENV['ISLAND_NAME'];
        $hotelName = $_ENV['HOTEL_NAME'];

        $response = [
            'isAvailable' => true,
            'island' => $islandName,
            'hotel' => $hotelName,
            'arrival_date' => $arrivalDate,
            'departure_date' => $departureDate,
            'total_cost' => $totalCost,
            'stars' => 2,
            'features' => [],
            'additional_info' => [
             'greeting' => 'Booking successful! You are expected to arrive on the '.$arrivalDate. 'and departure on the '.$departureDate.'
                                  for a total stay of '.$numberOfDays. '! Total cost estimated at: '.$totalCost.'. Thank you for choosing us!
                                  We await your arrival with much anticipation.'
            ]
        ];
        foreach($selectedFeaturesNames as $feature){
          $response['features'][] = [
            'name' => $feature['name'],
            'cost' => $feature['cost'],
          ];
        };

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // If booking fails, handle accordingly
        echo json_encode(["error" => "Booking failed. Please try again."]);
    }
}


function calculateDays(string $arrivalDate, string $departureDate): int
{
    $arrivalDate = new DateTime($arrivalDate);
    $departureDate = new DateTime($departureDate);
    $interval = $arrivalDate->diff($departureDate);
    return $interval->days;
}

function calculateCostOfFeatures(array $selectedFeatureIDs) : int
{
  $featureCost = 0;
  foreach ($selectedFeatureIDs as $feature) {
    $featureCost += 5;
  }
  return $featureCost;
}
// Function to calculate the cost based on room type and duration
function calculateCost(int $room_id, array $selectedFeatureIDs, int $numberOfDays): float
 {
   $cost = 0;

  if ($room_id == 1){
    $cost += 5;
  } else if($room_id == 2){
    $cost += 10;
  } else if($room_id == 3){
    $cost += 25;
  } else {
    echo 'ERROR - Room not found';
  }


  $cost = $cost * $numberOfDays;
  $cost = calcCostAfterDiscount($cost, calcDiscount($numberOfDays));
  $featureCost = calculateCostOfFeatures($selectedFeatureIDs);
  $cost = $cost + $featureCost;

  return $cost;
}
//calculations for discounts
function calcDiscount(int $days): float
{
    if ($days == 4) {
        $discount = 0.1;
    } else if ($days == 5) {
        $discount = 0.2;
    } else if ($days == 6) {
        $discount = 0.3;
    } else if ($days == 7) {
        $discount = 0.4;
    } else if ($days >= 8) {
        $discount = 0.5;
    } else {
        $discount = 0.0; // No discount for less than 4 days
    }

    return 1.0 - $discount;
}
function calcCostAfterDiscount(int $totalCost, float $discount): float
{
  $costAfterDiscount = $totalCost * $discount;
  return $costAfterDiscount;
}

// Function to handle the booking and charge the user
function bookRoom(string $user_id, int $room_id, string $arrivalDate, string $departureDate, float $totalCost, array $selectedFeatureIDs): bool
 {
  $room_id = $room_id;
  $arrival_date = $arrivalDate;
  $departure_date = $departureDate;
  $total_cost = $totalCost;
  $features = array_slice($selectedFeatureIDs, 0);
  insertBooking($user_id, $room_id, $arrival_date, $departure_date, $total_cost, $features);
  return true;
}

