<?php
declare(strict_types=1);


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



function insertBooking(string $user_id, int $room_id, string $arrival_date, string $departure_date, float $total_cost, array $features): void
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
function getBookings(string $user_id): array
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
function getBooking(string $booking_id): array
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
function getBookingFeatures(string $booking_id): array
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
function getRoom(string $room_id): array
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