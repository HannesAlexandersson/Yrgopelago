<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/connectToVacDB.php';
//initialize global variable for total points if its not already initialized
if(!isset($_SESSION['total_points'])){
$_SESSION['total_points'] = 0;
}
if (isset($_POST['btn'])) {
 // if the btn for inserting the data inside the file "loggbok" is pressed
if($_POST['btn'] === 'insert'){
  require __DIR__ . '/handleForm.php';
}
//save the tables as arrays to be able to iterate them
$vacations = getLoggs('../vacation/vacation.db');
$features = getFeats('../vacation/vacation.db');
}
// trying to build some sort of visual representation of the logbook:
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Loggbok</title>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>
  <div class="wrapper-insert">
    <p>Press the button to insert the loggbok file into the db</p>
    <form action="/vacation/vacation.php" method="post">

    <input type="hidden" name="btn" value="insert">
    <button id="btn" type="submit">Insert to DB</button>
</form>
  </div>
  <div class="content-wrapper loggbok">
    <h1>Logbook</h1>
    <table>
      <thead>
        <tr>
          <th>Island</th>
          <th>Hotel</th>
          <th>Check-in</th>
          <th>Check-out</th>
          <th>Cost</th>
          <th>Stars</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($vacations as $vacation) : ?>
          <tr>
            <td><?= $vacation['island_name'] ?></td>
            <td><?= $vacation['hotel_name'] ?></td>
            <td><?= $vacation['arrival_date'] ?></td>
            <td><?= $vacation['departure_date'] ?></td>
            <td><?= $vacation['total_cost'] ?></td>
            <td><?= $vacation['stars'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="content-wrapper features">
    <?php foreach($features as $feats) : ?>
        <h6>Feature name: <?=$feats['feat_name'] ?></h6>
    <?php endforeach;?>
  </div>

  <hr>
  <h1>Misc. calculations:</h1>
  <div class="wrapper-insert">
    <p>Press the button to calculate the total number of stars</p>
    <form action="/vacation/vacation.php" method="post">
        <input type="hidden" name="btn" value="calculate_stars">
        <button type="submit">Calculate Total Stars</button>
    </form>
    <?php
    if(isset($_POST['btn'])){
      if($_POST['btn'] === 'calculate_stars'){ ?>
        <p>Your total sum of stars: <?= getTotalStars() ?></p><?php
      }
    } ?>
  </div>
  <div class="wrapper-insert">
    <p>Press the button to calculate the total sum I've spent in others hotels</p>
    <form action="/vacation/vacation.php" method="post">
        <input type="hidden" name="btn" value="spending">
        <button type="submit">Calculate Total Sum</button>
    </form>
    <?php
    if(isset($_POST['btn'])){
      if($_POST['btn'] === 'spending'){ ?>
        <p>Your total spending sum: <?= getTotalMoneySpent() ?></p><?php
      }
    } ?>
  </div>

  <hr>
  <h1>Points calculations: (Total Points: <?= $_SESSION['total_points']?>)</h1>
  <div class="wrapper-insert">
    <p>Press the button to calculate the total number of features</p>
    <form action="/vacation/vacation.php" method="post">
        <input type="hidden" name="btn" value="calculate_features">
        <button type="submit">Calculate Total Features</button>
    </form>
    <?php
    if(isset($_POST['btn'])){
      if($_POST['btn'] === 'calculate_features'){ ?>
        <p>Your total number of features: <?= getTotalFeatures() ?></p><?php
      }
    } ?>
  </div>
  <div class="wrapper-insert">
    <p>Press the button to calculate The total points gathered from staying at hotels ( each hotel gives 2 points)</p>
    <form action="/vacation/vacation.php" method="post">
        <input type="hidden" name="btn" value="hotel_points">
        <button type="submit">Calculate Points</button>
    </form>
    <?php
    if(isset($_POST['btn'])){
      if($_POST['btn'] === 'hotel_points'){ ?>
        <p>Your points from hotels: <?= getPointsFromHotels() ?></p><?php
      }
    } ?>
  </div>
  <div class="wrapper-insert">
    <p>Press the button to calculate the total number of days stayed at other hotels. Each day gives 1 point</p>
    <form action="/vacation/vacation.php" method="post">
        <input type="hidden" name="btn" value="days">
        <button type="submit">Calculate Days</button>
    </form>
    <?php
    if(isset($_POST['btn'])){
      if($_POST['btn'] === 'days'){ ?>
        <p>Your points from total number of days: <?= getTotalDays() ?></p><?php
      }
    } ?>
  </div>
  <div class="wrapper-insert">
    <p>Press the button to calculate The total points from different starcategories. Each star category gives 3 points</p>
    <form action="/vacation/vacation.php" method="post">
        <input type="hidden" name="btn" value="star_points">
        <button type="submit">Calculate Points</button>
    </form>
    <?php
    if(isset($_POST['btn'])){
      if($_POST['btn'] === 'star_points'){ ?>
        <p>Your points from star-categories: <?= getStarsPoints() ?></p><?php
      }
    } ?>
  </div>
</body>
</html>