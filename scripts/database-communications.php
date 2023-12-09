<?php
declare(strict_types=1);


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
        echo "Failed to connect to the database";
        throw $e;
    }
    return $db;
}



function insertBooking($user_id, $room_id, $arrival_date, $departure_date, $total_cost, $features)
{
    $db = connectToDatabase('/database/avalon.db');

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
    // Get the booking_id of the booking we just inserted
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
// a function to get the bookings that are booked to populate the calendar with
function getBookingsForCalendar()
{
    $db = connectToDatabase('avalon.db');


    $query = "SELECT arrival_date, departure_date FROM bookings";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $bookings;
}