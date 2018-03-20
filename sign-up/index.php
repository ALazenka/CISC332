<?php
  include '../components/sql-connect.php';
  session_start();
  
  $customer = "";
  $get_customers = "SELECT id, account_number, firstname, lastname, street, town, postalcode, province, country, phone_number, email_address, cc_number, cc_expiry_date FROM customer WHERE account_number=" . $_SESSION['account_number'];
  $result = $conn->query($get_customers);

  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $customer = $row;
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
    <?php include '../components/side-menu.php'; ?>
    <main class="sign-up-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="sign-up-picture-section">
        <div class="sign-up-default-image"><i class="far fa-user" id="sign-up-image" title="Profile"></i></div>
        <div class="sign-up-account-number">Create Account</div>
      </div>
      <div class="sign-up-info-section">
        <div class="sign-up-info-name">
          <div class="sign-up-info-name-badge">Name:</div>
          <input class="sign-up-info-name-field" type="text" />
        </div>
        <div class="sign-up-info-email">
          <div class="sign-up-info-email-badge">Email:</div>
          <input class="sign-up-info-email-field" type="text" />
        </div>
        <div class="sign-up-info-address">Address</div>
        <div class="sign-up-info-address-indent">
          <div class="flex-display">
            <div class="sign-up-info-address-badge">Street: <input type="text" /></div>
          </div>
          <div class="flex-display">
            <div class="sign-up-info-address-badge">Town: <input type="text" /></div>
          </div>
          <div class="flex-display">
            <div class="sign-up-info-address-badge">State/Province: <input type="text" /></div>
          </div>
          <div class="flex-display">
            <div class="sign-up-info-address-badge">Country: <input type="text" /></div>
          </div>
          <div class="flex-display">
            <div class="sign-up-info-address-badge">Postal Code: <input type="text" /></div>
          </div>
        </div>
        <div class="sign-up-info-phone">
          <div class="sign-up-info-phone-badge">Phone Number:</div>
          <input class="sign-up-info-phone-field" type="text" />
        </div>
        <div class="sign-up-info-credit-card">
          <div class="sign-up-info-credit-card-badge">Credit Card:</div>
          <input class="sign-up-info-credit-card-field" type="text" />
        </div>
        <div class="sign-up-info-credit-card-expiry">
          <div class="sign-up-info-credit-card-expiry-badge">Credit Card Expiry:</div>
          <input class="sign-up-info-credit-card-expiry-field" type="text" />
        </div>
      </div>
      <div class="sign-up-save-section">
        <button id="sign-up-save-button" type="button" class="btn btn-secondary">Save</button>
      </div>
    </main>
  </body>
</html>