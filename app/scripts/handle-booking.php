<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/bankfunction.php';

require __DIR__ . '/app/database/database-communications.php';
require __DIR__ . '/hotelFunctions.php';
use Dotenv\Dotenv;
use GuzzleHttp\Client;
// Load the environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Access the values from dotenv
$islandName = $_ENV['ISLAND_NAME'];
$hotelName = $_ENV['HOTEL_NAME'];
$hotelstars = $_ENV['STARS'];
$hotelManager = $_ENV['USER_NAME'];
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Parse JSON data from the JavaScript request
  $postData = json_decode(file_get_contents("php://input"), true);

  if ($postData === null) {
    // Respond with an error if the JSON data is invalid
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid JSON data']);
    exit();
  }
  else{

    // basic variables needed for the booking
    $selectedFeaturesNames = []; // I need the actual feature names for the response aswell for visual purposes
    $selectedFeatureIDs = []; // but for the bookinglogic i need the feature ids

    // Retrieve form data
    $room_id = isset($postData["room_id"]) ? (int) $postData["room_id"] : 0;
    // if validation fails, send error message and exit the script to abort the booking
    if ($room_id === 0) {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Invalid room ID']);
      exit();  }

    //validate the features
    $selectedFeatureIDs = isset($postData["features"]) && $postData["features"] !== null ? array_map('intval', $postData["features"]) : [];
    //validate the dates
    $arrivalDate = isset($postData["arrivalDate"]) ? filter_var($postData["arrivalDate"], FILTER_SANITIZE_STRING) : "";
    $departureDate = isset($postData["departureDate"]) ? filter_var($postData["departureDate"], FILTER_SANITIZE_STRING) : "";

    //sanitize the user_id
    $user_id = isset($postData["user_id"]) ? htmlspecialchars($postData["user_id"]) : "";
    // if validation fails, send error message and exit the script to abort the booking
    if (!isValidUuid($user_id) && $room_id && $arrivalDate && $departureDate) {
      exit();
    }else{
      $room;
      // Validate room, I need the actual room name for the response aswell for visual purposes
      if($room_id == 1){
        $room = 'The Gaze';
      }else if($room_id == 2){
        $room = 'The Tranquility';
      }else if($room_id == 3){
        $room = 'The Presidential';
      };

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
    $bankResponseValidation = validateTransferCode($user_id, $totalCost);

    if($bankResponseValidation['error']){ // if validation of transfercode fails, abort the booking and alert the user via json to javascript clientside and then the js alerts the user
      echo json_encode(["error" => "Transfer code validation failed. Please try again."]);
      exit();
    } else if ($bankResponseValidation["amount"] > $totalCost) { //if the user tryes to use a transfercode with not enough money on it we abort the booking
      echo json_encode(["error" => "Transfer code validation failed. Please try again."]);
      exit();
    } else { // if all goes well we deposit the transfercode and then we perform the booking
        // Deposit the transfer code
        $bankResponseDeposit = depositTransferCode($user_id, $hotelManager);
    }



    // Perform the booking and check the result
    $bookingResult = bookRoom($user_id, $room_id, $arrivalDate, $departureDate, $totalCost, $selectedFeatureIDs);
    if ($bookingResult) {
      // Check the result of the booking and send the response as json
      checkBooking($room, $bookingResult, $arrivalDate, $departureDate, $numberOfDays, $totalCost, $selectedFeaturesNames);
    } else {
      // If booking fails send error message and exit the script
      echo json_encode(["error" => "Booking failed. Please try again."]);
      exit();
    }

    exit();
    }
  }
}

// Function to check the result of the booking
function checkBooking(string $room, bool $bookingResult, string $arrivalDate, string $departureDate, int $numberOfDays, float $totalCost, array $selectedFeaturesNames): void
{

    // if booking is successful, send the response as json back to the client
    if ($bookingResult) {
        $islandName = $_ENV['ISLAND_NAME'];
        $hotelName = $_ENV['HOTEL_NAME'];
        $hotelstars = $_ENV['STARS'];

        $response = [
            'island' => $islandName,
            'hotel' => $hotelName,
            'arrival_date' => $arrivalDate,
            'departure_date' => $departureDate,
            'total_cost' => $totalCost,
            'stars' => $hotelstars,
            'features' => [],
            'additional_info' => [
              'booking-result' => $bookingResult,
              'greeting' => 'Booking successful! You are expected to arrive on the '.$arrivalDate. 'and departure on the '.$departureDate.'
                            for a total stay of '.$numberOfDays. 'days! You´ll be staying in the room '.$room.'. Total cost estimated at: '.$totalCost.'. Thank you for choosing us!
                            We await your arrival with much anticipation.'
            ]
        ];
        foreach($selectedFeaturesNames as $feature){ //the selected features are added to the response here
          $response['features'][] = [
            'name' => $feature['name'],
            'cost' => $feature['cost'],
          ];
        };

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // If booking fails send error message and exit the script
        echo json_encode(["error" => "Booking failed. Please try again."]);
    }
}


function calculateDays(string $arrivalDate, string $departureDate): int
{
    $arrivalDate = new DateTime($arrivalDate);
    $departureDate = new DateTime($departureDate);
    $interval = $arrivalDate->diff($departureDate);
    return $interval->days +1;// need to add 1 day becouse the interval is calculated from midnight to midnight,so ex 1 of dec to 3 of dec would count as 2 days otherwise
}
// Function to calculate the cost of the features, for simplicity we just add 5 for each feature, BUT the manager have the ability to change the price in the admin page. So for that reason I fetch the price from the db to ensure that the calculation is correct no matter the price
function calculateCostOfFeatures(array $selectedFeatureIDs): int
{
    $selectedFeaturecost = 0;
    $featureCostMassage = getFeaturePrice(1);
    $featureCostBedtime = getFeaturePrice(2);
    $featureCostHotsprings = getFeaturePrice(3);

    foreach ($selectedFeatureIDs as $feature) {
        if ($feature == 1) {
            $featureCost = $featureCostMassage;
            $selectedFeaturecost += $featureCost;
        } elseif ($feature == 2) {
            $featureCost = $featureCostBedtime;
            $selectedFeaturecost += $featureCost;
        } elseif ($feature == 3) {
            $featureCost = $featureCostHotsprings;
            $selectedFeaturecost += $featureCost;
        }
    }

    return $selectedFeaturecost;
}
// Function to calculate the cost based on room type and duration
function calculateCost(int $room_id, array $selectedFeatureIDs, int $numberOfDays): float
 {

   $roomCostPerNight = getRoomPrice($room_id);

  // simple calculation, cost is based on room type times the number of days
  $cost = $roomCostPerNight * $numberOfDays;
  // calculate discount, the discount is based on the number of days (see function calcDiscount)
  $cost = calcCostAfterDiscount($cost, calcDiscount($numberOfDays));
  // calculate the cost of the features, the feautures cost is not subject to discount. So it is added after the discount is calculated
  $featureCost = calculateCostOfFeatures($selectedFeatureIDs);
  $cost = $cost + $featureCost;

  return $cost;
}
//calculations for discounts. The longer the user stays at the hotel the bigger the discount
function calcDiscount(int $days): float
{
    if ($days == 4) {
        $discount = 0.1; // 10% discount
    } else if ($days == 5) {
        $discount = 0.2; // 20% discount
    } else if ($days == 6) {
        $discount = 0.3; // 30% discount
    } else if ($days == 7) {
        $discount = 0.4; // 40% discount
    } else if ($days >= 8) {
        $discount = 0.5; // 50% discount
    } else {
        $discount = 0.0; // No discount for less than 4 days
    }

    return 1.0 - $discount;
}
// Function to calculate the cost after discount
function calcCostAfterDiscount(int $totalCost, float $discount): float
{
  $costAfterDiscount = $totalCost * $discount;
  return $costAfterDiscount;
}

// Function to handle the booking and charge the user
function bookRoom(string $user_id, int $room_id, string $arrivalDate, string $departureDate, float $totalCost, array $selectedFeatureIDs): bool
 {
  try{
      $room_id = $room_id;
      $arrival_date = $arrivalDate;
      $departure_date = $departureDate;
      $total_cost = $totalCost;
      $features = array_slice($selectedFeatureIDs, 0);
      insertBooking($user_id, $room_id, $arrival_date, $departure_date, $total_cost, $features); // insert the booking into the database
      return true; // return true if the booking was successful
  } catch (PDOException $e) {
      error_log('Failed to connect to the database: ' . $e->getMessage());
      throw $e;
      return false; // return false if the booking failed
  }
}

