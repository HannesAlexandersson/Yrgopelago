<?php
declare(strict_types=1);
// get the data from the loggbok

$dbName = '../vacation/vacation.db';
function connectToVAC(string $dbName): PDO
{
  $dbPath = __DIR__ . '/' . $dbName;
  $db = "sqlite:$dbPath";

  // Open the database file and catch the exception if it fails.
  try {
      $db = new PDO($db);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
      echo "Failed to connect to the database". $e->getMessage();
      throw $e;
  }
  return $db;

}
// inserts the booking response from the file into the vacation DB
function insertLoggbok(array $vacations): void
{
  $db = connectToVAC('../vacation/vacation.db');
  $query = "INSERT INTO loggbok (island_name, hotel_name, arrival_date, departure_date, total_cost, stars)
            VALUES (:island_name, :hotel_name, :arrival_date, :departure_date, :total_cost, :stars)";

  $statement = $db->prepare($query);
  foreach($vacations['vacation'] as $vacation){
  $statement->bindParam(':island_name', $vacation['island']);
  $statement->bindParam(':hotel_name', $vacation['hotel']);
  $statement->bindParam(':arrival_date', $vacation['arrival_date']);
  $statement->bindParam(':departure_date', $vacation['departure_date']);
  $statement->bindParam(':total_cost', $vacation['total_cost']);
  $statement->bindParam(':stars', $vacation['stars']);

  $statement->execute();

  $loggbok_id = $db->lastInsertId();
  if(!empty($vacation['features'])){
    foreach($vacation['features'] as $feature){
      $query = "INSERT INTO features (feat_name, logg_id)
                VALUES (:feat_name, :logg_id)";
      $stmnt = $db->prepare($query);
      $stmnt->bindParam(':feat_name', $feature['name']);
      $stmnt->bindParam(':logg_id', $loggbok_id);
      $stmnt->execute();
    }
    }
  }
}

function getLoggs(string $dbName): array
{
  $dbPath = __DIR__ . '/' .$dbName;
    $db = "sqlite:$dbPath";

    // Open the database file and catch the exception if it fails.
    try {
        $db = new PDO($db);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Failed to connect to the database". $e->getMessage();
        throw $e;
    }
    $vacations = $db->query("
    SELECT *
    FROM loggbok
")->fetchAll();
return $vacations; //returns the loggbok table as an asc array for me to iterate
}

function getFeats(string $dbName): array
{
  $dbPath = __DIR__ . '/' .$dbName;
  $db = "sqlite:$dbPath";

  // Open the database file and catch the exception if it fails.
  try {
      $db = new PDO($db);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
      echo "Failed to connect to the database". $e->getMessage();
      throw $e;
  }
  $feats = $db->query("
  SELECT *
  FROM features
  ")->fetchAll();
  return $feats; //returns the features table as an asc array for me to iterate
}