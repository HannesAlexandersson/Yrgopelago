<?php
declare(strict_types=1);
require __DIR__ . '/connectToVacDB.php';
if (isset($_POST['btn'])) {
  require __DIR__ . '/handleForm.php';
}
// trying to build some sort of visual representation of the logbook
$vacations = getLoggs('../vacation/vacation.db');
$features = getFeats('../vacation/vacation.db');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div class="wrapper-insert">
    <p>Press the button to insert the loggbok file into the db</p>
    <form action="/vacation/vacation.php" method="post">

    <input type="hidden" name="btn" value="true">
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
          <th>Room</th>
          <th>Check-in</th>
          <th>Check-out</th>
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
</body>
</html>