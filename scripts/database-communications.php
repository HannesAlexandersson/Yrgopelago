<?php
declare(strict_types=1);
session_start();
// get the bookings from the database as an associative array to be able to show in the calender when a room is already booked
$bookings = getBookingsForCalendarRender();
// Process the result into an array with FullCalendar event structure
$events = [];
foreach ($bookings as $booking) {
    $events[] = [
        'title' => 'Room ' . $booking['room_id'],
        'start' => $booking['arrival_date'],
        'end' => $booking['departure_date'],
    ];
}

// Return the events as JSON
header('Content-Type: application/json');
echo json_encode($events);

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
//I need an function that connects to the database and I cant seem to get acces to the other one I have in the other file without breaking the program
/* function databaseConnect(string $dbName): object
{
  $dbPath = __DIR__ . '/' . $dbName;
  $db = "sqlite:$dbPath";
  try {
      $db = new PDO($db);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
      error_log("Failed to connect to the database: " . $e->getMessage());
      throw $e;
  }
  return $db;
} */