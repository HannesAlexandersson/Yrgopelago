<?php
session_start();
require __DIR__ . '/../scripts/database-communications.php';
// Check if the I am logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  // If not logged in, redirect to the login page
  header("Location: /index.php");
  exit();
} else {
  // Handle form submission to update room price
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $roomId = intval($_POST["roomId"]);
      $newPrice = floatval($_POST["newPrice"]);



      // Function to update room price
      function updateRoomPrice($roomId, $newPrice) {
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
      updateRoomPrice($roomId, $newPrice);
  }

  else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $featureId = intval($_POST["featureId"]);
    $newPriceFeature = floatval($_POST["newPriceFeature"]);


    function updateFeaturePrice($featureId, $newPriceFeature) {
      $db = connectToDatabase('../database/avalon.db');
      $query = "UPDATE features SET price = :newPrice WHERE feature_id = :featureId";
      try {
          $stmnt = $db->prepare($query);
          $stmnt->bindParam(':newPrice', $newPriceFeature);
          $stmnt->bindParam(':roomId', $featureId);
          $stmnt->execute();
      } catch (PDOException $e) {
          die("Error updating feature price: " . $e->getMessage());
      }
  }
  updateFeaturePrice($featureId, $newPriceFeature);

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