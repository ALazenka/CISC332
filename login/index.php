<?php
  session_start();
  $_SESSION["account_number_error"] = false;
  $_SESSION["password_error"] = false;
  if (isset($_POST['login_button'])) {
    $account_number = $_POST['account_number'];
    $user_password = $_POST['password'];
  
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cisc332";
    $customer_credentials = "";

    $conn = new mysqli($servername, $username, $password, $dbname);
  

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
  
    $validate_customer = "SELECT account_number, password FROM customer WHERE account_number='" . $account_number . "'";
    $result = $conn->query($validate_customer);
    
    if (!$result) {
      echo 'Could not run query: ' . mysql_error();
      exit;
    }
  
    $conn->close();
  
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $customer_credentials = $row;
      }
      if ($customer_credentials["password"] == $user_password) {
        $_SESSION["account_number"] = $customer_credentials["account_number"];
        $_SESSION["logged_in"] = true;
        header("Location: ../dashboard");
        exit();
      } else {
        $_SESSION["password_error"] = true;
      }
    } else {
      $_SESSION["account_number_error"] = true;
    }
  }
?>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login - OMTS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="login.css" />
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="home-page">
      <div class="sign-in">
        <div class="logo-container">
          <i class="fas fa-video logo"></i>
        </div>
        <h3>OMTS - Group 28</h3>
        <form action="index.php" method="POST">
          <input
            type="text"
            placeholder="Account Number"
            name="account_number"
            class="login-account-number form-control"
            value="<?php if (isset($_POST['account_number'])) { echo $_POST['account_number']; } ?>"
          />
          <?php
            if ($_SESSION["account_number_error"]) {
            ?>
            <div style="color:red;text-align:center;">Account Number Not Found!</div>
            <?php
            }
          ?>
          <input type="password" placeholder="Password" name="password" class="login-password form-control" />
          <?php
            if ($_SESSION["password_error"]) {
            ?>
            <div style="color:red;text-align:center;">Password Incorrect!</div>
            <?php
            }
          ?>
          <input type="submit" name="login_button" class="login-button btn btn-info" value="Login" />
        </form>
      </div>
    </div>
  </body>
</html>