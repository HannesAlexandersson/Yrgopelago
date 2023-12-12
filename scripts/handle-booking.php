<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..'); // Adjust the path accordingly
$dotenv->load();

// Access the values
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

$arrivalDate = isset($postData["arrivalDate"]) ? filter_var($postData["arrivalDate"], FILTER_SANITIZE_STRING) : "";
$departureDate = isset($postData["departureDate"]) ? filter_var($postData["departureDate"], FILTER_SANITIZE_STRING) : "";


 // Validate and sanitize user_id NEED TO ADD VALIDATION OF TRANSFERCODE
 $user_id = isset($postData["user_id"]) ? htmlspecialchars($postData["user_id"]) : "";
if (empty($user_id)) {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Invalid user_id']);
  exit();
}

$selectedFeaturesNames = []; // I need the actual feature names for the response aswell for visual purposes
$selectedFeatureIDs = []; // but for the bookinglogic i need the feature ids
// Check if features are selected
$selectedFeatureIDs = isset($postData["features"]) && $postData["features"] !== null ? array_map('intval', $postData["features"]) : [];
foreach($selectedFeatureIDs as $feature){
  if($feature == 1){
    $feature1['name'] = 'Massage Therapy';
  /*   $feature1['cost'] = 5;
    $feature1['id'] = 1;  OM INTE LOOPEN NEDANFÖR FUNKAR SÅ KÖR DENNA ISTÄLLET, MEN BEHÖVER LOOPEN FÖR ADMINSIDAN??*/
    array_push($selectedFeaturesNames, $feature1);
    }else if($feature == 2){
    $feature2['name'] = 'Bedtime Storyteller';
   /*  $feature2['cost'] = 5;
    $feature2['id'] = 2; */
    array_push($selectedFeaturesNames, $feature2);
    }else if($feature == 3){
    $feature3['name'] = 'Underground Hotsprings';
    /* $feature3['cost'] = 5;
    $feature3['id'] = 3; */
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


  // calculate toatal number fo days the user stays at the hotel
  $numberOfDays = calculateDays($arrivalDate, $departureDate);

  // Calculate cost
  $totalCost = calculateCost($room_id, $selectedFeatureIDs, $numberOfDays);


  // Perform the booking and charge the user
  $bookingResult = bookRoom($user_id, $room_id, $arrivalDate, $departureDate, $totalCost, $selectedFeatureIDs);

  checkBooking($bookingResult, $arrivalDate, $departureDate, $numberOfDays, $totalCost, $selectedFeaturesNames);

  // Debugging: Check the response
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

// a function to get the bookings that are booked to populate the calendar with
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