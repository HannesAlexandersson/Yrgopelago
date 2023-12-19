<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/../scripts/database-communications.php';


// Check if I am logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  // If not logged in, redirect to the login page
  header("Location: /index.php");
  exit();

  // else the hotel manager is logged in
} else {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {// if the form is submitted
    if (isset($_POST["roomId"]) && isset($_POST["newPrice"])) {// and the room price form is submitted
      $roomId = intval($_POST["roomId"]);
      $newPrice = floatval($_POST["newPrice"]);

      // Function to update room price in the db
      function updateRoomPrice(int $roomId, float $newPrice): void
       {
          $db = connectToDatabase('../database/avalon.db');
          $query = "UPDATE rooms SET price = :newPrice WHERE room_id = :roomId";
          try {
              $stmnt = $db->prepare($query);
              $stmnt->bindParam(':newPrice', $newPrice);
              $stmnt->bindParam(':roomId', $roomId);
              $stmnt->execute();
          } catch (PDOException $e) {
              die("Error updating room price: " . $e->getMessage());
          }
      }
      updateRoomPrice($roomId, $newPrice);// call the function to update the room price
/*-----------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------*/
    } elseif (isset($_POST["featureId"]) && isset($_POST["newPriceFeature"])) { // Handle form submission to update feature price if the feature price form is submitted
      $featureId = intval($_POST["featureId"]);
      $newPriceFeature = floatval($_POST["newPriceFeature"]);

      // Function to update feature price in the db
      function updateFeaturePrice(int $featureId, float $newPriceFeature): void
      {
        $db = connectToDatabase('../database/avalon.db');
        $query = "UPDATE features SET price = :newPrice WHERE feature_id = :featureId";
        try {
            $stmnt = $db->prepare($query);
            $stmnt->bindParam(':newPrice', $newPriceFeature);
            $stmnt->bindParam(':featureId', $featureId);
            $stmnt->execute();
        } catch (PDOException $e) {
            die("Error updating feature price: " . $e->getMessage());
        }
    }
    updateFeaturePrice($featureId, $newPriceFeature);// call the function to update the feature price

    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>

</head>
<body>

<h1>Welcome to the Admin Page</h1>
<p>You can update room prices here.</p>


<form action="admin.php" method="post">
    <label for="roomId">Room ID:</label>
    <input type="text" id="roomId" name="roomId" required>

    <label for="newPrice">New Price:</label>
    <input type="text" id="newPrice" name="newPrice" required>

    <button type="submit">Update Price</button>
</form>

<form action="admin.php" method="post">
    <label for="featureId">feature ID:</label>
    <input type="text" id="featureId" name="featureId" required>

    <label for="newPriceFeature">New Price:</label>
    <input type="text" id="newPriceFeature" name="newPriceFeature" required>

    <button type="submit">Update Price</button>
</form>



</body>
</html>