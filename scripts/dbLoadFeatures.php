<?php

$database = new PDO('sqlite:database/avalon.db');

$proInfo = $database->query("
	SELECT *
	FROM features
	")->fetchAll();

?>