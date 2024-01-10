<?php
declare(strict_types=1);
$logbookFilePath = __DIR__ . '/logbook.json';
//read the file
$loggbok= json_decode(file_get_contents(__DIR__ . '/logbook.json'), true);
if (isset($_POST['btn'])) {
  //build the html table with the content of the loggbok
  insertLoggbok($loggbok);

  // Clear the content of the "vacation" array so there is space for new "vacations"
  $logbook['vacation'] = [];
  // Save the "cleared" logbook back to the file
  file_put_contents($logbookFilePath, json_encode($logbook, JSON_PRETTY_PRINT));
}