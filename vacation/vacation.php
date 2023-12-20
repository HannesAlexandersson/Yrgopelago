<?php

declare(strict_types=1);

header('Content-Type:application/json');

$vacation= json_decode(file_get_contents(__DIR__ . '/vacation/logbook.json'), true);

