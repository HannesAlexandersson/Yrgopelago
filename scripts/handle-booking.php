<?php
declare(strict_types=1);
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $room = $_POST["room"];
    $arrivalDate = $_POST["arrivalDate"];
    $departureDate = $_POST["departureDate"];

    // Calculate cost (you can adjust this based on your pricing strategy)
    $totalCost = calculateCost($room, $arrivalDate, $departureDate, $costOffeatures);

    // Perform the booking and charge the user
    $bookingResult = bookRoom($room, $arrivalDate, $departureDate, $totalCost);

    // Check the result of the booking
    if ($bookingResult) {
        // If booking is successful, perform other actions (e.g., notify user, update database, etc.)
        echo "Booking successful! Total cost: $totalCost";
    } else {
        // If booking fails, handle accordingly
        echo "Booking failed. Please try again.";
    }
}
function calculateDays($arrivalDate, $departureDate) {
    $arrivalDate = new DateTime($arrivalDate);
    $departureDate = new DateTime($departureDate);
    $interval = $arrivalDate->diff($departureDate);
    return $interval->days;
}

function calculateCostOfFeatures($features) {
    $cost = 0;
    foreach ($features as $feature) {
        $cost += $feature;
    }
    return $cost;
}
// Function to calculate the cost based on room type and duration
function calculateCost($room, $arrivalDate, $departureDate, $costOffeatures) {
    $suits = [
      'The Presidential' => 25,
      'The Tranquility' => 10,
      'The Gaze' => 5
    ];
    $cost = 0;
    foreach ($suits as $suit => $value) {
      if ($room == $suit){
        $cost = $value;
      }else{
        echo 'ERROR - Room not found';
      }
    }
    $days = calculateDays($arrivalDate, $departureDate);
    $cost = $cost * $days;

    return $cost;
}

// Function to handle the booking and charge the user
function bookRoom($room, $arrivalDate, $departureDate, $totalCost) {
    // Your booking logic here (replace with your own logic)
    // This is just a placeholder, replace it with your actual booking process.
    // You may want to interact with your database, external services, etc.
    // For now, this function always returns true (booking is successful).
    return true;
}

?>