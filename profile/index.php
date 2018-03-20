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
    <title>Profile - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="profile.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="profile-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="profile-picture-section">
        <div class="profile-default-image"><i class="far fa-user" id="profile-image" title="Profile"></i></div>
        <div class="profile-account-number"><?php echo $customer["account_number"]; ?></div>
      </div>
      <div class="profile-info-section">
        <div class="profile-info-name">
          <div class="profile-info-name-badge">Name:</div>
          <div class="profile-info-name-field"><?php echo $customer["firstname"] . " " . $customer["lastname"]; ?></div>
        </div>
        <div class="profile-info-email">
          <div class="profile-info-email-badge">Email:</div>
          <div class="profile-info-email-field"><?php echo $customer["email_address"]; ?></div>
        </div>
        <div class="profile-info-address">Address</div>
        <div class="profile-info-address-indent">
          <div class="flex-display">
            <div class="profile-info-address-badge">Street: <?php echo $customer["street"]; ?></div>
          </div>
          <div class="flex-display">
            <div class="profile-info-address-badge">Town: <?php echo $customer["town"]; ?></div>
          </div>
          <div class="flex-display">
            <div class="profile-info-address-badge">State/Province: <?php echo $customer["province"]; ?></div>
          </div>
          <div class="flex-display">
            <div class="profile-info-address-badge">Country: <?php echo $customer["country"]; ?></div>
          </div>
          <div class="flex-display">
            <div class="profile-info-address-badge">Postal Code: <?php echo $customer["postalcode"]; ?></div>
          </div>
        </div>
        <div class="profile-info-phone">
          <div class="profile-info-phone-badge">Phone Number:</div>
          <div class="profile-info-phone-field"><?php echo $customer["phone_number"]; ?></div>
        </div>
        <div class="profile-info-credit-card">
          <div class="profile-info-credit-card-badge">Credit Card:</div>
          <div class="profile-info-credit-card-field"><?php echo $customer["cc_number"]; ?></div>
        </div>
        <div class="profile-info-credit-card-expiry">
          <div class="profile-info-credit-card-expiry-badge">Credit Card Expiry:</div>
          <div class="profile-info-credit-card-expiry-field"><?php echo $customer["cc_expiry_date"]; ?></div>
        </div>
      </div>
      <div class="profile-edit-section">
        <button id="profile-edit-button" type="button" class="btn btn-secondary">Edit</button>
      </div>
    </main>
  </body>
</html>