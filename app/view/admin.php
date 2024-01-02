<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/../../app/database/database-communications.php';


// Check if I(the hotel manager) am logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  // If not logged in, redirect to the login page
  header("Location: /index.php");
  exit();

  // else the hotel manager(me) is logged in
} else {
  /* -----------------------------CHANGE ROOM PRICE-------------------------------------------------------------*/
  if ($_SERVER["REQUEST_METHOD"] == "POST") {// if the form is submitted
    if (isset($_POST["roomId"]) && isset($_POST["newPrice"])) {// and the room price form is submitted
      $roomId = intval($_POST["roomId"]);
      $newPrice = floatval($_POST["newPrice"]);

      // Function to update room price in the db
      function updateRoomPrice(int $roomId, float $newPrice): void
       {
          $db = connectToDatabase('avalon.db');
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
/*--------------------------CHANGE FEATURE PRICE-------------------------------------------------------*/
    } elseif (isset($_POST["featureId"]) && isset($_POST["newPriceFeature"])) { // Handle form submission to update feature price if the feature price form is submitted
      $featureId = intval($_POST["featureId"]);
      $newPriceFeature = floatval($_POST["newPriceFeature"]);

      // Function to update feature price in the db
      function updateFeaturePrice(int $featureId, float $newPriceFeature): void
      {
        $db = connectToDatabase('avalon.db');
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
/*----------------------------------------------------------------------------------------------------------- */
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
<div class="transfercode-wrapper">
  <button id="get-transfercodes">Show guests</button>
  <div class="content-box">

  </div>
</div>
<script>// gets the transfer codes from the json file and displays them in a table
document.getElementById('get-transfercodes').addEventListener('click', function() {
  // Fetch the entire JSON file
  fetch('/../app/scripts/validation_response.json')
    .then(response => response.json())
    .then(data => {
      // Update the content-box with the fetched data
      updateContentBox(data);
    })
    .catch(error => { // If there is an error fetching the data, log it to the console
      console.error('Error fetching data:', error);
    });
});

function updateContentBox(data) {
  // Get the content-box element
  var contentBox = document.querySelector('.content-box');

  // Clear existing content
  contentBox.innerHTML = '';

  // Create a table to display the data
  var table = document.createElement('table');
  table.border = '1';

  // Create table header
  var thead = document.createElement('thead');
  var headerRow = document.createElement('tr');
  var headers = ['Transfer Code', 'From Account', 'Amount'];

  headers.forEach(headerText => {
    var th = document.createElement('th');
    th.textContent = headerText;
    headerRow.appendChild(th);
  });

  thead.appendChild(headerRow);
  table.appendChild(thead);

  // Create table body
  var tbody = document.createElement('tbody');

  // Loop through the data and create table rows
  data.guests.forEach(guest => {
    var row = document.createElement('tr');

    // Create table cells for each property
    var transferCodeCell = document.createElement('td');
    transferCodeCell.textContent = guest.transferCode;
    row.appendChild(transferCodeCell);

    var fromAccountCell = document.createElement('td');
    fromAccountCell.textContent = guest.fromAccount;
    row.appendChild(fromAccountCell);

    var amountCell = document.createElement('td');
    amountCell.textContent = guest.amount;
    row.appendChild(amountCell);

    tbody.appendChild(row);
  });

  table.appendChild(tbody);

  // Append the table to the content-box
  contentBox.appendChild(table);
}
</script>



</body>
</html>