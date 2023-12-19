<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/CentralBankService.php';
require __DIR__ . '/database-communications.php';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  $postData = json_decode(file_get_contents("php://input"), true);

  $room_id = filter_var($postData["room"], FILTER_VALIDATE_INT);
  // Validate and sanitize arrivalDate and departureDate
  $arrivalDate = filter_var($postData['arrivalDate'], FILTER_SANITIZE_STRING);
  $departureDate = filter_var($postData['departureDate'], FILTER_SANITIZE_STRING);

  // Check if the conversion and filtering were successful
  if ($room_id === false || $arrivalDate === false || $departureDate === false) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid data received.']);
    exit();
  }


  // Check room availability
  $isAvailable = checkRoomAvailability($room_id, $arrivalDate, $departureDate);
  if (!$isAvailable) {
    // Room not available, send error response and exit
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Room not available for the selected dates. Please try another date.']);
    exit();
  }else {
    header('Content-Type: application/json');
      echo json_encode(['isAvailable' => true]);
  }

}


// Function to check room availability
function checkRoomAvailability(int $room_id, string $arrivalDate, string $departureDate): bool
{
    $bookings = getBookingsForCalendar($room_id); // this function returns an array of booked dates from the DB

    // Check if there are no bookings, consider the room available
    if (empty($bookings)) {
        return true;
    }
    else
  {

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
  }
  return true; // Room is available
}