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

function getTotalStars(): int
{
    $db = connectToVAC('../vacation/vacation.db');

    try {
        $query = "SELECT SUM(stars) as total_stars FROM loggbok";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        $totalStars = $result['total_stars'];

        return (int)$totalStars;
    } catch (PDOException $e) {
        error_log("Error calculating total stars: " . $e->getMessage());
        return 0;
    }
}

function getTotalFeatures(): int
{
  $db = connectToVAC('../vacation/vacation.db');
  try {
    $query = "SELECT COUNT(DISTINCT feat_name) as total_features FROM features"; // we only count distinct features since we only gets points for those
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    $totalFeatures = $result['total_features'];

    return (int)$totalFeatures;
} catch (PDOException $e) {
    error_log("Error calculating total features: " . $e->getMessage());
    return 0;
}
}

function getTotalMoneySpent(): int
{
    $db = connectToVAC('../vacation/vacation.db');

    try {
        $query = "SELECT SUM(total_cost) as total_spent FROM loggbok";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        $totalSpent = $result['total_spent'];

        return (int)$totalSpent;
    } catch (PDOException $e) {
        error_log("Error calculating total spending: " . $e->getMessage());
        return 0;
    }
}