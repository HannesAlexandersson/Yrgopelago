<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/connectToVacDB.php';
if (isset($_POST['btn'])) {
 // if the btn for inserting the data inside the file "loggbok" is pressed
if($_POST['btn'] === 'insert'){
  require __DIR__ . '/handleForm.php';
}
// trying to build some sort of visual representation of the logbook
$vacations = getLoggs('../vacation/vacation.db');
$features = getFeats('../vacation/vacation.db');
}
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
    <p>Press the button to calculate the total sum I've spent in others hotels</p>
    <form action="/vacation/vacation.php" method="post">
        <input type="hidden" name="btn" value="spending">
        <button type="submit">Calculate Total Features</button>
    </form>
    <?php
    if(isset($_POST['btn'])){
      if($_POST['btn'] === 'spending'){ ?>
        <p>Your total spending sum: <?= getTotalMoneySpent() ?></p><?php
      }
    } ?>
</div>
</body>
</html>