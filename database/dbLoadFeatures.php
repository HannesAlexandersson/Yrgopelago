<?php
declare(strict_types=1);
function connect(string $dbName): array
{
    $dbPath = __DIR__ . '/' .$dbName;
    $db = "sqlite:$dbPath";

    // Open the database file and catch the exception if it fails.
    try {
        $db = new PDO($db);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Failed to connect to the database";
        throw $e;
    }
    $features = $db->query("
    SELECT *
    FROM features
")->fetchAll();
return $features; //returns the features table as an asc array for me to iterate
}

?>