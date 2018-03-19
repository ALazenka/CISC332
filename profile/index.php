<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cisc332";
$customer = "";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
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
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Profile - OMTS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="profile.css" />
    <script src="profile.js"></script>
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </head>
  <body>
    <nav id="side-menu">
      <ul class="menu-list">
        <a class="toggle close" id="close" href="#">
          <i class="far fa-times-circle" id="menu-close"></i>
        </a>
        <a class="profile-item" href="/CISC332/profile">
          <i class="far fa-user" id="profile-item" title="Profile"></i>
        </a>
        <a class="menu-item" href="/CISC332/showtimes">
          <i class="fas fa-video" id="menu-item" title="Showtimes"></i>
        </a>
        <a class="menu-item" href="/CISC332/movie-list">
          <i class="fas fa-film" id="menu-item" title="Movie List"></i>
        </a>
        <a class="menu-item" href="/CISC332/tickets">
          <i class="fas fa-ticket-alt" id="menu-item" title="Tickets"></i>
        </a>
        <a class="menu-item" href="/CISC332/reviews">
          <i class="far fa-star" id="menu-item" title="Reviews"></i>
        </a>
        <a class="menu-item" href="/CISC332/members">
          <i class="fas fa-users" id="menu-item" title="Members (Admin Only)"></i>
        </a>
      </ul>
    </nav>
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