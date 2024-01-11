<?php
declare(strict_types=1);

// connection to the vacation DB
function connectToVAC(string $dbName): object //PDO
{
  $dbPath = __DIR__ . '/' . $dbName;
  $db = "sqlite:$dbPath";

  // Open the database file
  try {
      $db = new PDO($db);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {//error handling
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
    // get the id of the last inserted entry to enter correct "foreign" key in the junction table
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

  // Open a connection to the DB
  try {
      $db = new PDO($db);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {//error handling
      echo "Failed to connect to the database". $e->getMessage();
      throw $e;
  }
  // get the data from the DB with the query
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

  // Open a connection to the DB
  try {
      $db = new PDO($db);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {//error handling
      echo "Failed to connect to the database". $e->getMessage();
      throw $e;
  }
  // get the data from the DB with the query
  $feats = $db->query("
  SELECT *
  FROM features
  ")->fetchAll();
  return $feats; //returns the features table as an asc array for me to iterate
}


/*---------------CALCULATIONS FUNCTIONS------------*/

/*----Misc. Calcs-----*/
function getTotalStars(): int
{
  //connect to DB
  $db = connectToVAC('../vacation/vacation.db');

  try {//query for the calculation (sum up total amount of stars)
      $query = "SELECT SUM(stars) as total_stars FROM loggbok";
      $stmt = $db->prepare($query);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);


      $totalStars = $result['total_stars'];
      // returns the total amount of stars
      return (int)$totalStars;
    //error handling
  } catch (PDOException $e) {
      error_log("Error calculating total stars: " . $e->getMessage());
      return 0;
  }
}

function getTotalMoneySpent(): int
{
  // connect to DB
  $db = connectToVAC('../vacation/vacation.db');

  try {
      $query = "SELECT SUM(total_cost) as total_spent FROM loggbok";// query for the calculation that sums up the total amount of money spent
      $stmt = $db->prepare($query);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);


      $totalSpent = $result['total_spent'];

      return (int)$totalSpent;
  } catch (PDOException $e) {//error handling
      error_log("Error calculating total spending: " . $e->getMessage());
      return 0;
  }
}
/*----Point calcs----*/
function getTotalFeatures(): int
{
  $db = connectToVAC('../vacation/vacation.db');
  try {
    $query = "SELECT COUNT(DISTINCT feat_name) as total_features FROM features"; // we only count distinct features since we only gets points for those
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalFeatures = $result['total_features'];

    $_SESSION['total_points'] += $totalFeatures;// add the points to the global variable that holds the total amount of points
    return (int)$totalFeatures;
  } catch (PDOException $e) {//error handling
      error_log("Error calculating total features: " . $e->getMessage());
      return 0;
  }
}

function getPointsFromHotels(): int
{
  $db = connectToVAC('../vacation/vacation.db');

  try {
    $query = "SELECT COUNT(*) * 2 AS total_points FROM loggbok";// count every hotel I have stayed at and give 2 point for each hotel
    $statement = $db->query($query);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $_SESSION['total_points'] += (int)$result['total_points'];// add the points to the global variable that holds the total amount of points
    return (int)$result['total_points'];
  } catch (PDOException $e) {//error handling
      error_log("Error calculating points from hotel: " . $e->getMessage());

  }
}

function getTotalDays(): int
{
  $db = connectToVAC('../vacation/vacation.db');
  try{
  $query = "SELECT SUM(julianday(departure_date) - julianday(arrival_date)) AS total_days FROM loggbok";//count every day that I have spent in an hotel, each day give 1 point
  $statement = $db->query($query);
  $result = $statement->fetch(PDO::FETCH_ASSOC);

  $_SESSION['total_points'] += (int)$result['total_days'];// add the points to the global variable that holds the total amount of points
  return (int)$result['total_days'];
  } catch (PDOException $e) {//error handling
    error_log("Error calculating total number of days: " . $e->getMessage());
  }
}

function getStarsPoints(): int
{
  $db = connectToVAC('../vacation/vacation.db');
  try{
  $query = "SELECT COUNT(DISTINCT stars) * 3 AS total_star_points FROM loggbok"; //count every star category I have been at by counting the rows distinct, each star category gives 3 points
  $statement = $db->query($query);
  $result = $statement->fetch(PDO::FETCH_ASSOC);

  $_SESSION['total_points'] += (int)$result['total_star_points'];// add the points to the global variable that holds the total amount of points
  return (int)$result['total_star_points'];
  } catch (PDOException $e) {//error handling
    error_log("Error calculating total number of points from stars: " . $e->getMessage());
  }
}