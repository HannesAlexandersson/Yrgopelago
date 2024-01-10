<?php
declare(strict_types=1);
$loggbok= json_decode(file_get_contents(__DIR__ . '/logbook.json'), true);
if (isset($_POST['btn'])) {

  insertLoggbok($loggbok);

}