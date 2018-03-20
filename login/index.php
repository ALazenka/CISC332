<?php
  include '../components/sql-connect.php';
  session_start();
  $_SESSION["account_number_error"] = false;
  $_SESSION["password_error"] = false;
  
  if (isset($_POST['login_button'])) {
    $account_number = $_POST['account_number'];
    $user_password = $_POST['password'];
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
        header("Location: ../showtimes");
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
    <title>Login - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="login.css" />
    <?php include '../components/head-contents.php'; ?>
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
            <div class="login-error" style="color:red;text-align:center;">Account Number Not Found!</div>
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
          <a class="sign-up-link" href="/CISC332/sign-up">Don't have an account? Sign up here!</a>
        </form>
      </div>
    </div>
  </body>
</html>