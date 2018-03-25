<?php
  include '../components/sql-connect.php';
  session_start();
  $_SESSION["password_match_error"] = false;
  $_SESSION["duplicate_email"] = false;
  $_SESSION["duplicate_account_number"] = false;
  
  if (isset($_POST["create-button"])) {
    $duplicate_email = "SELECT * FROM customer WHERE email_address='" . $_POST['email'] . "'";
    $result_de = $conn->query($duplicate_email);

    $duplicate_account_number = "SELECT * FROM customer WHERE account_number='" . $_POST['account_number'] . "'";
    $result_dan = $conn->query($duplicate_account_number);

    if ($result_de->num_rows > 0) {
      $_SESSION["duplicate_email"] = true;
    } else if ($result_dan->num_rows > 0) {
      $_SESSION["duplicate_account_number"] = true;
    } else if ($_POST["password"] != $_POST["confirm_password"]) {
      $_SESSION["password_match_error"] = true;
    } else {
      $customer_first_name = $_POST["firstname"];
      $customer_last_name = $_POST["lastname"];
      $customer_account_number = $_POST["account_number"];
      $customer_email = $_POST["email"];
      $customer_password = $_POST["password"];
      $customer_street = $_POST["street"];
      $customer_town = $_POST["town"];
      $customer_province = $_POST["province"];
      $customer_country = $_POST["country"];
      $customer_postalcode = $_POST["postalcode"];
      $customer_phone_number = $_POST["phone_number"];
      $customer_cc_number = $_POST["cc_number"];
      $customer_cc_expiry = $_POST["cc_expiry"];
      $create_user = "INSERT INTO customer
                      VALUES ('$customer_account_number', '$customer_password', '$customer_first_name', '$customer_last_name', '$customer_street', '$customer_town', '$customer_postalcode', '$customer_province', '$customer_country', '$customer_phone_number', '$customer_email', '$customer_cc_number', '$customer_cc_expiry', '0', '')";
      $result = $conn->query($create_user);
      if (!$result) {
        echo 'Could not run query: ' . mysql_error();
        exit;
      }
      $_SESSION["account_create_success"] = true;
      $_SESSION["password_match_error"] = false;
      $_SESSION["duplicate_email"] = false;
      $_SESSION["account_number"] = $customer_account_number;
      header("Location: ../theater-complex");
    }
  }
  $conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Sign Up - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="sign-up.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <main class="sign-up-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="sign-up-picture-section">
        <div class="sign-up-default-image"><i class="far fa-user" id="sign-up-image" title="Profile"></i></div>
        <div class="sign-up-account-number">Create Account</div>
      </div>
      <div class="sign-up-info-section">
        <form action="index.php" method="POST">
          <div class="sign-up-info-firstname">
            <input class="sign-up-info-name-field form-control" name="firstname" placeholder="First Name" type="text" />
          </div>
          <div class="sign-up-info-lastname">
            <input class="sign-up-info-name-field form-control" name="lastname" placeholder="Last Name" type="text" />
          </div>
          <div class="sign-up-info-account-number">
            <input class="sign-up-info-name-field form-control" name="account_number" placeholder="Account Number" type="text" />
          </div>
          <?php
            if ($_SESSION["duplicate_account_number"]) {
          ?>
          <div style="color:red;text-align:right;">Account Number taken, please choose another.</div>
          <?php
            }
          ?>
          <div class="sign-up-info-email">
            <input class="sign-up-info-name-field form-control" name="email" placeholder="Email Address" type="text" />
          </div>
          <?php
            if ($_SESSION["duplicate_email"]) {
          ?>
          <div style="color:red;text-align:right;">Email has been taken by another account!</div>
          <?php
            }
          ?>
          <div class="sign-up-info-password">
            <input class="sign-up-info-name-field form-control" name="password" placeholder="Password" type="password" />
          </div>
          <?php
            if ($_SESSION["password_match_error"]) {
          ?>
          <div style="color:red;text-align:right;">Passwords do not match!</div>
          <?php
            }
          ?>
          <div class="sign-up-info-password">
            <input class="sign-up-info-name-field form-control" name="confirm_password" placeholder="Confirm Password" type="password" />
          </div>
          <div class="sign-up-info-address">Address:</div>
          <div class="sign-up-info-address-indent">
            <div class="flex-display">
              <input class="sign-up-info-name-field form-control" name="street" placeholder="Street" type="text" />
            </div>
            <div class="flex-display margin-top">
              <input class="sign-up-info-name-field form-control" name="town" placeholder="Town/City" type="text" />
            </div>
            <div class="flex-display margin-top">
              <input class="sign-up-info-name-field form-control" name="province" placeholder="State/Province" type="text" />
            </div>
            <div class="flex-display margin-top">
              <input class="sign-up-info-name-field form-control" name="country" placeholder="Country" type="text" />
            </div>
            <div class="flex-display margin-top">
              <input class="sign-up-info-name-field form-control" name="postalcode" placeholder="Postal Code" type="text" />
            </div>
          </div>
          <div class="sign-up-info-phone">
            <input class="sign-up-info-name-field form-control" name="phone_number" placeholder="Phone Number" type="text" />
          </div>
          <div class="sign-up-info-credit-card">
            <input class="sign-up-info-name-field form-control" name="cc_number" placeholder="Credit Card Number" type="text" />
          </div>
          <div class="sign-up-info-credit-card-expiry">
            <input class="sign-up-info-name-field form-control" name="cc_expiry" placeholder="Credit Card Expiry Date" type="text" />
          </div>
          <input id="sign-up-save-button" name="create-button" type="submit" class="btn btn-success" value="Create Account" />
        </form>
      </div>
    </main>
  </body>
</html>