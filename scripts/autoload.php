<?php
declare(strict_types=1);
session_start();


require __DIR__ . '/../database/dbLoadFeatures.php';
require __DIR__ . '/../database/dbloadRooms.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/database-communications.php';
require __DIR__ . '/CentralBankService.php';
require __DIR__ . '/hotelFunctions.php';
require __DIR__ . '/handle-booking.php';
require __DIR__ . '/copyright-functions.php';
// Fetch the global configuration array.
$config = require __DIR__ . '/config.php';
// Setup the database connection.
$database = new PDO($config['database_path']);