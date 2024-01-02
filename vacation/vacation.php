<?php

declare(strict_types=1);

header('Content-Type:application/json');
// trying to build some sort of visual representation of the logbook
$vacation= json_decode(file_get_contents(__DIR__ . '/vacation/logbook.json'), true);// get the data from the json file and decode it, it should contain all my visits to other hotels, IE the booking responses I have gotten

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div class="content-wrapper">
    <h1>Logbook</h1>
    <table>
      <thead>
        <tr>
          <th>Island</th>
          <th>Hotel</th>
          <th>Room</th>
          <th>Check-in</th>
          <th>Check-out</th>
          <th>Transfer code</th>
          <th>Features</th>
          <th>Stars</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($vacation as $vacation) : ?>
          <tr>
            <td><?= $vacation['Island'] ?></td>
            <td><?= $vacation['Hotel'] ?></td>
            <td><?= $vacation['room'] ?></td>
            <td><?= $vacation['check-in'] ?></td>
            <td><?= $vacation['check-out'] ?></td>
            <td><?= $vacation['transfer code'] ?></td>
            <td><?= $vacation['features'] ?></td>
            <td><?= $vacation['Stars'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>