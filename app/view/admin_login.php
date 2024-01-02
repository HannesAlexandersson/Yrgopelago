<?php
session_start();
require __DIR__. '/../../vendor/autoload.php';
use Dotenv\Dotenv;
// get the data from the login form
if(isset($_POST['username']) && isset($_POST['password'])){
  // store the data in variables and sanitize
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);


   // Load the environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Access the values from dotenv
$adminUserName = $_ENV['API_KEY'];
$adminPassword = $_ENV['ADMIN_PASSWORD'];
// check if the username and password are correct
if($username == $adminUserName && $password == $adminPassword){
  // if they are correct, set the session variable to true and redirect to the admin page
  $_SESSION['logged_in'] = true;
    header('Location: admin.php');
    exit;
}
else{// if they are not correct, echo an error message and let the user try again
    echo 'Wrong username or password';
}

}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
  </head>
  <body>
    <div id="login-container">
      <h2>Login</h2>
      <form action="admin_login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="adminUsername" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="adminPassword" name="password" required>

        <button >Login</button>
      </form>
    </div>
  </body>
</html>