<?php
  include '../components/sql-connect.php';
  session_start();

  if (!isset($_SESSION["account_number"]) || $_SESSION["account_number"] < 0) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }
  
  $customer = "";
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  $member_account_number = -1;
  $member_edit = false;
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'account_number=') !== false) {
      $member_account_number = str_replace("account_number=", "", $item);
    }
    if (strpos($item, 'edit=') !== false) {
      $member_edit = str_replace("edit=", "", $item);
    } 
  };

  if (isset($_POST["edit-profile-button"])) {
    $duplicate_email = "SELECT * FROM customer WHERE email_address='" . $_POST['email'] . "' AND id <> '" . $_POST["edit_id"] . "'";
    $result_de = $conn->query($duplicate_email);

    $duplicate_account_number = "SELECT * FROM customer WHERE account_number='" . $_POST['account_number'] . "' AND id <> '" . $_POST["edit_id"] . "'";
    $result_dan = $conn->query($duplicate_account_number);

    if ($result_de->num_rows > 0) {
      $_SESSION["duplicate_email_edit"] = true;
      header("Location: ../profile/?edit=true");
    } else if ($result_dan->num_rows > 0) {
      $_SESSION["duplicate_account_number_edit"] = true;
      header("Location: ../profile/?edit=true");
    } else if ($_POST["password"] != $_POST["confirm_password"]) {
      $_SESSION["password_match_error_edit"] = true;
      header("Location: ../profile/?edit=true");
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
      $update_profile = "UPDATE customer SET account_number = '$customer_account_number', password = '$customer_password', firstname = '$customer_first_name', lastname = '$customer_last_name', street = '$customer_street', town = '$customer_town', postalcode = '$customer_postalcode', province = '$customer_province', country = '$customer_country', phone_number = '$customer_phone_number', email_address = '$customer_email', cc_number = '$customer_cc_number', cc_expiry_date = '$customer_cc_expiry' WHERE customer.account_number='$customer_account_number'";
      $conn->query($update_profile);
      $_SESSION["account_create_success_edit"] = true;
      $_SESSION["password_match_error_edit"] = false;
      $_SESSION["duplicate_email_edit"] = false;
      header("Location: ../profile");
    }
  }

  $get_customer = "SELECT id, account_number, password, firstname, lastname, street, town, postalcode, province, country, phone_number, email_address, cc_number, cc_expiry_date FROM customer WHERE account_number=" . ($member_account_number >= 0 ? $member_account_number : $_SESSION['account_number']);
  $result = $conn->query($get_customer);

  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $customer = $row;
    }
  }

  $tickets = [];

  if (!$member_edit && $_SESSION["user_role"] == 1 && $member_account_number >= 0) {
    $get_tickets = "SELECT reservation.id as id, movie.title as movie_title, reservation.tickets_reserved, showing.start_time FROM reservation JOIN showing ON showing.id=reservation.showing_id JOIN movie on movie.id=showing.movie_id WHERE reservation.account_number=" . $_SESSION["account_number"];
    $result = $conn->query($get_tickets);
    if (!$result) {
      echo 'Could not run query: ' . mysql_error();
      exit;
    }
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        array_push($tickets, $row);
      }
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
        <div class="profile-account-number"><?php echo ($member_edit ? 'Edit Profile' : $customer["account_number"]); ?></div>
      </div>
      <div class="profile-info-section">
        <?php
          if (!$member_edit) {
        ?>
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
            <div class="profile-info-address-badge">Street:</div>
            <div class="profile-info-address-indent-field"><?php echo $customer["street"]; ?></div>
          </div>
          <div class="flex-display">
            <div class="profile-info-address-badge">Town:</div>
            <div class="profile-info-address-indent-field"><?php echo $customer["town"]; ?></div>
          </div>
          <div class="flex-display">
            <div class="profile-info-address-badge">State/Province:</div>
            <div class="profile-info-address-indent-field"><?php echo $customer["province"]; ?></div>
          </div>
          <div class="flex-display">
            <div class="profile-info-address-badge">Country:</div>
            <div class="profile-info-address-indent-field"><?php echo $customer["country"]; ?></div>
          </div>
          <div class="flex-display">
            <div class="profile-info-address-badge">Postal Code:</div>
            <div class="profile-info-address-indent-field"><?php echo $customer["postalcode"]; ?></div>
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
        <?php
          } else {
        ?>
        <form action="index.php" method="POST">
          <div class="edit-profile-info-firstname">
            <div class="profile-info-name-badge">First Name:</div>
            <input class="edit-profile-info-name-field form-control" name="firstname" value="<?php echo $customer["firstname"]; ?>" placeholder="First Name" type="text" />
          </div>
          <div class="edit-profile-info-lastname">
            <div class="profile-info-name-badge">Last Name:</div>
            <input class="edit-profile-info-name-field form-control" name="lastname" value="<?php echo $customer["lastname"]; ?>"  placeholder="Last Name" type="text" />
          </div>
          <div class="edit-profile-info-account-number">
            <div class="profile-info-name-badge">Account Number:</div>
            <input class="edit-profile-info-name-field form-control" name="account_number" value="<?php echo $customer["account_number"]; ?>"  placeholder="Account Number" type="text" />
          </div>
          <?php
            if (isset($_SESSION["duplicate_account_number_edit"]) && $_SESSION["duplicate_account_number_edit"]) {
          ?>
          <div style="color:red;text-align:right;">Account Number taken, please choose another.</div>
          <?php
            }
          ?>
          <div class="edit-profile-info-email">
            <div class="profile-info-name-badge">Email:</div>
            <input class="edit-profile-info-name-field form-control" name="email" value="<?php echo $customer["email_address"]; ?>"  placeholder="Email Address" type="text" />
          </div>
          <?php
            if (isset($_SESSION["duplicate_email_edit"]) && $_SESSION["duplicate_email_edit"]) {
          ?>
          <div style="color:red;text-align:right;">Email has been taken by another account!</div>
          <?php
            }
          ?>
          <div class="edit-profile-info-password">
            <div class="profile-info-name-badge">Password:</div>
            <input class="edit-profile-info-name-field form-control" name="password" value="<?php echo $customer["password"]; ?>"  placeholder="Password" type="password" />
          </div>
          <?php
            if (isset($_SESSION["password_match_error_edit"]) && $_SESSION["password_match_error_edit"]) {
          ?>
          <div style="color:red;text-align:right;">Passwords do not match!</div>
          <?php
            }
          ?>
          <div class="edit-profile-info-password">
            <div class="profile-info-name-badge">Confirm Password:</div>
            <input class="edit-profile-info-name-field form-control" name="confirm_password" value="<?php echo $customer["password"]; ?>"  placeholder="Confirm Password" type="password" />
          </div>
          <div class="edit-profile-info-address">Address:</div>
          <div class="edit-profile-info-address-indent">
            <div class="flex-display">
            <div class="profile-info-name-badge">Street:</div>
            <input class="edit-profile-info-address-indent-field form-control" name="street" value="<?php echo $customer["street"]; ?>"  placeholder="Street" type="text" />
            </div>
            <div class="flex-display margin-top">
            <div class="profile-info-name-badge">Town/City:</div>
            <input class="edit-profile-info-address-indent-field form-control" name="town" value="<?php echo $customer["town"]; ?>"  placeholder="Town/City" type="text" />
            </div>
            <div class="flex-display margin-top">
            <div class="profile-info-name-badge">Province/State:</div>
            <input class="edit-profile-info-address-indent-field form-control" name="province" value="<?php echo $customer["province"]; ?>"  placeholder="State/Province" type="text" />
            </div>
            <div class="flex-display margin-top">
            <div class="profile-info-name-badge">Country:</div>
            <input class="edit-profile-info-address-indent-field form-control" name="country" value="<?php echo $customer["country"]; ?>"  placeholder="Country" type="text" />
            </div>
            <div class="flex-display margin-top">
            <div class="profile-info-name-badge">Postal Code:</div>
            <input class="edit-profile-info-address-indent-field form-control" name="postalcode" value="<?php echo $customer["postalcode"]; ?>" placeholder="Postal Code" type="text" />
            </div>
          </div>
          <div class="edit-profile-info-phone">
            <div class="profile-info-name-badge">Phone Number:</div>
            <input class="edit-profile-info-name-field form-control" name="phone_number" value="<?php echo $customer["phone_number"]; ?>" placeholder="Phone Number" type="text" />
          </div>
          <div class="edit-profile-info-credit-card">
            <div class="profile-info-name-badge">Credit Card Number:</div>
            <input class="edit-profile-info-name-field form-control" name="cc_number" value="<?php echo $customer["cc_number"]; ?>" placeholder="Credit Card Number" type="text" />
          </div>
          <div class="edit-profile-info-credit-card-expiry">
            <div class="profile-info-name-badge">Credit Card Expiry Date:</div>
            <input class="edit-profile-info-name-field form-control" name="cc_expiry" value="<?php echo $customer["cc_expiry_date"]; ?>" placeholder="Credit Card Expiry Date" type="text" />
          </div>
          <div class="edit-button-container">
            <input type="hidden" name="edit_id" value="<?php echo $customer["id"]; ?>" />
            <input id="edit-profile-save-button" name="edit-profile-button" type="submit" class="btn btn-success" value="Update Profile" />
          </div>
        </form>
        <?php } ?>
        <?php if (!$member_edit && $_SESSION["user_role"] == 1 && $member_account_number >= 0) { ?>
        <div class="tickets-info-section-badge">Member's Tickets:</div>
        <div class="tickets-info-section">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Movie Name</th>
                <th scope="col">Tickets Reserved</th>
                <th scope="col">Start Time</th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($tickets as &$ticket) {
                  
              ?>
              <tr class="table-item">
                <th scope="row"><?php echo $ticket['id']; ?></th>
                <td><?php echo $ticket['movie_title']; ?></td>
                <td><?php echo $ticket['tickets_reserved']; ?></td>
                <td><?php echo $ticket['start_time']; ?></td>
              </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
        </div>
        <?php } ?>
      </div>
      <div class="profile-edit-section">
        <?php
          if ($_SESSION["account_number"] == $customer["account_number"] && !$member_edit) {
        ?>
          <a id="profile-edit-button" class="btn btn-primary" href="/CISC332/profile/?edit=true" role="button">Edit</a>
        <?php
          }
        ?>
      </div>
    </main>
  </body>
</html>