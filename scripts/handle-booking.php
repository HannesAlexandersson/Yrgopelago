<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';
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

  // Check room availability
  $isAvailable = checkRoomAvailability($room_id, $arrivalDate, $departureDate);
  if (!$isAvailable) {
    // Room not available, send error response and exit
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Room not available for the selected dates. Please try another date.']);
    exit();
}


// calculate toatal number fo days the user stays at the hotel
$numberOfDays = calculateDays($arrivalDate, $departureDate);

// Calculate cost
$totalCost = calculateCost($room_id, $selectedFeatureIDs, $numberOfDays);




// Validate TRANSFERCODE check if code is unused and if the user has enough money on the code
$baseUrl = 'https://www.yrgopelag.se/centralbank';
if (empty($user_id)) {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Invalid transfercode']);
  exit();
}else{
  $response = [];
  $response = validateTransferCode($user_id, $totalCost, $baseUrl);
}

  // Perform the booking and charge the user
  $bookingResult = bookRoom($user_id, $room_id, $arrivalDate, $departureDate, $totalCost, $selectedFeatureIDs);

  checkBooking($bookingResult, $arrivalDate, $departureDate, $numberOfDays, $totalCost, $selectedFeaturesNames);
  exit();
}

// Function to check the result of the booking
function checkBooking(bool $bookingResult, string $arrivalDate, string $departureDate, int $numberOfDays, int $totalCost, array $selectedFeaturesNames): void
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
function calculateCost(int $room_id, array $selectedFeatureIDs, int $numberOfDays): int
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

  $featureCost = calculateCostOfFeatures($selectedFeatureIDs);
  $cost += $featureCost;

  return $cost;
}

// Function to handle the booking and charge the user
function bookRoom(string $user_id, int $room_id, string $arrivalDate, string $departureDate, int $totalCost, array $selectedFeatureIDs): bool
 {
  $room_id = $room_id;
  $arrival_date = $arrivalDate;
  $departure_date = $departureDate;
  $total_cost = $totalCost;
  $features = array_slice($selectedFeatureIDs, 0);
  insertBooking($user_id, $room_id, $arrival_date, $departure_date, $total_cost, $features);
  return true;
}

// Function to check room availability
function checkRoomAvailability(int $room_id, string $arrivalDate, string $departureDate): bool
{
    $bookings = getBookingsForCalendar($room_id); // this function returns an array of booked dates from the DB

    // Check if there are no bookings, consider the room available
    if (empty($bookings)) {
        return true;
    }


    // Check if the room is available for the selected dates
    foreach ($bookings as $booking) {
      $bookingStartDate = strtotime((string)$booking['arrival_date']);
      $bookingEndDate = strtotime((string)$booking['departure_date']);
      $selectedStartDate = strtotime((string)$arrivalDate);
      $selectedEndDate = strtotime((string)$departureDate);

        // Check for date overlap
        if (
            ($selectedStartDate >= $bookingStartDate && $selectedStartDate < $bookingEndDate) ||
            ($selectedEndDate > $bookingStartDate && $selectedEndDate <= $bookingEndDate) ||
            ($selectedStartDate <= $bookingStartDate && $selectedEndDate >= $bookingEndDate)
        ) {
            return false; // Room is not available
        }
    }

  return true; // Room is available
}

// a function to get the bookings that are booked to populate the calendar with when a new booking are made
function getBookingsForCalendar(int $room_id): array
{
    $db = connectToDatabase('../database/avalon.db');


    $query = "SELECT booking_id, arrival_date, departure_date FROM bookings WHERE room_id = :room_id";
    try {
    $stmt = $db->prepare($query);
    $stmt->bindParam(':room_id', $room_id);
    $stmt->execute();

    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $bookings;
    } catch (PDOException $e) {
      // Handle the error
      echo "Error: " . $e->getMessage();
  }
}
function connectToDatabase(string $dbName): object
{
    $dbPath = __DIR__ . '/' . $dbName;
    $db = "sqlite:$dbPath";

    // Open the database file and catch the exception if it fails.
    try {
        $db = new PDO($db);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Failed to connect to the database". $e->getMessage();
        throw $e;
    }
    return $db;
}



function insertBooking($user_id, $room_id, $arrival_date, $departure_date, $total_cost, $features)
{
    $db = connectToDatabase('../database/avalon.db');

    // Insert booking into bookings table
    $query = "INSERT INTO bookings (user_id, room_id, arrival_date, departure_date, total_cost)
              VALUES (:user_id, :room_id, :arrival_date, :departure_date, :total_cost)";

    $statement = $db->prepare($query);
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':room_id', $room_id);
    $statement->bindParam(':arrival_date', $arrival_date);
    $statement->bindParam(':departure_date', $departure_date);
    $statement->bindParam(':total_cost', $total_cost);

    $statement->execute();

    // Get the booking_id of the booking we just inserted to be able to insert the features in the feature table
    $booking_id = $db->lastInsertId();

    // Insert features into booking_features table if needed and connect the picked features to the booking
    if (!empty($features)) {
        foreach ($features as $feature_id) {
            $query = "INSERT INTO booking_features (booking_id, feature_id) VALUES (:booking_id, :feature_id)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':booking_id', $booking_id);
            $stmt->bindParam(':feature_id', $feature_id);
            $stmt->execute();
        }
    }
}



// a function to get the bookings for a specefic user if the same user has multiple bookings
function getBookings($user_id)
{
    $db = connectToDatabase('avalon.db');

    $query = "SELECT * FROM bookings WHERE user_id = :user_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();

    $bookings = $statement->fetchAll();

    return $bookings;
}



// a function to get the booking of a specific user if the user has only one booking
function getBooking($booking_id)
{
    $db = connectToDatabase('avalon.db');

    $query = "SELECT * FROM bookings WHERE id = :booking_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':booking_id', $booking_id);
    $statement->execute();

    $booking = $statement->fetch();

    return $booking;
}



// a function to get the  picked features of a specific booking
function getBookingFeatures($booking_id)
{
    $db = connectToDatabase('avalon.db');

    $query = "SELECT * FROM booking_features WHERE booking_id = :booking_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':booking_id', $booking_id);
    $statement->execute();

    $booking_features = $statement->fetchAll();

    return $booking_features;
}



// a function to get the rooms that are available for booking
function getRoom($room_id)
{
    $db = connectToDatabase('avalon.db');

    $query = "SELECT * FROM rooms WHERE id = :room_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':room_id', $room_id);
    $statement->execute();

    $room = $statement->fetch();

    return $room;
}

/* CALENDER LOGIC Populate the calender when page loads with existing bookings */
function getBookingsForCalendarRender(): array
{
  try {
      $db = connectToDatabase('../database/avalon.db');
      $query = "SELECT room_id, arrival_date, departure_date FROM bookings";
      $stmt = $db->prepare($query);
      $stmt->execute();
      $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $bookings;
  } catch (PDOException $e) {
      // Log or handle the error
      error_log("Error fetching bookings: " . $e->getMessage());
      return [];
  }
}

if (isset($_GET['calendar']) && $_GET['calendar'] === 'true') {

  $bookings = getBookingsForCalendarRender();
  $events = [];
  foreach ($bookings as $booking) {
    if($booking['room_id'] == 1){
      $events[] = [
          'title' => 'The Gaze',
          'start' => $booking['arrival_date'],
          'end' => $booking['departure_date'],
      ];
    }else if($booking['room_id'] == 2){
      $events[] = [
          'title' => 'The Tranquility',
          'start' => $booking['arrival_date'],
          'end' => $booking['departure_date'],
      ];
    }else if($booking['room_id'] == 3){
      $events[] = [
          'title' => 'The Presidential',
          'start' => $booking['arrival_date'],
          'end' => $booking['departure_date'],
      ];
    }
  }

  // Return the events as JSON
  header('Content-Type: application/json');
  echo json_encode($events);
}


/*Validating transfercode */


function validateTransferCode(string $transfercode, int $totalCost, string $baseUrl): array
{
  $transfercodeEndpoint = $baseUrl . '/transferCode';

$client = new Client();

try {
    // Send a POST request to the central bank
    $response = $client->post($transfercodeEndpoint, [
        'form_params' => [
            'transferCode' => $transfercode,
            'totalcost' => $totalCost
        ]
    ]);

    // Get the response body
    $responseBody = $response->getBody()->getContents();

    // Decode the JSON response
    $decodedResponse = json_decode($responseBody, true);

    // Write the response to a JSON file
    file_put_contents('transfercodeResponse.json', json_encode($decodedResponse));
    header('Content-Type: application/json');
    echo json_encode($decodedResponse);
    // Return the decoded response
    return $decodedResponse;
} catch (\Exception $e) {
    // If an exception occurs, return an error message
    return ['error' => $e->getMessage()];
}
}