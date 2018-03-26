<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"])) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] != 1) {
    $_SESSION["user_role_redirect"] = true;
    header("Location: ../reservation-complex");
  }

  $complex_info = [];
  $complex_id = 0;
  $complex_edit = false;
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'complex_id=') !== false) {
      $complex_id = str_replace("complex_id=", "", $item);
    } else if(strpos($item, 'edit_id=') !== false) {
      $complex_id = str_replace("edit_id=", "", $item);
      $_SESSION["edit_complex_id"] = $complex_id;
      $complex_edit = true;
    }
  };
  
  if (isset($_POST["complex-edit-button"])) {
    $name = $_POST["name"];
    $phone_number = $_POST["phone_number"];
    $street = $_POST["street"];
    $town = $_POST["town"];
    $province = $_POST["province"];
    $country = $_POST["country"];
    $postalcode = $_POST["postalcode"];
    $complex_id = $_SESSION["edit_complex_id"];
    $create_complex = "UPDATE Theater_Complex SET name = '$name', street = '$street', town = '$town', postalcode = '$postalcode', province = '$province', country = '$country', phone_number = '$phone_number' WHERE id = '$complex_id'";
    $conn->query($create_complex);
    $_SESSION["complex_edit_success"] = true;
    header("Location: ../theater-complex");
  }

  $get_complex_info = "SELECT * FROM theater_complex WHERE id = $complex_id";
  $result = $conn->query($get_complex_info);
  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  $conn->close();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $complex_info = $row;
    }
  }
?>
<html>
  <head>
    <title>Theater Complex Details - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="theater-complex-details.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="theater-complex-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="theater-complex-picture-section">
        <div class="theater-complex-default-image"><i class="fas fa-tv" id="theater-complex-image" title="Complex"></i></div>
        <div class="theater-complex-account-number"><?php echo $complex_info["id"]; ?></div>
      </div>
      <div class="theater-complex-info-section">
        <?php if (!$complex_edit) { ?>
        <div class="theater-complex-info-name">
          <div class="theater-complex-info-name-badge">Name:</div>
          <div class="theater-complex-info-name-field"><?php echo $complex_info["name"]; ?></div>
        </div>
        <div class="theater-complex-info-phone">
          <div class="theater-complex-info-phone-badge">Phone Number:</div>
          <div class="theater-complex-info-phone-field"><?php echo $complex_info["phone_number"]; ?></div>
        </div>
        <div class="theater-complex-info-address">
          <div class="theater-complex-info-address-badge">Address:</div>
          <div class="theater-complex-info-address-field"><?php echo $complex_info['street'] . ' ' . $complex_info['town'] . ', ' . $complex_info['province'] . ', ' . $complex_info['country']; ?></div>
        </div>
        <?php } else { ?>
        <form action="index.php" method="POST">
          <div class="theater-complex-create-info-name">
            <input type="text" class="form-control" name="name" value="<?php echo $complex_info["name"]; ?>" placeholder="Complex Name" />
          </div>
          <div class="theater-complex-create-info-phone">
            <input type="text" class="form-control" name="phone_number" value="<?php echo $complex_info["phone_number"]; ?>" placeholder="Phone Number" />
          </div>
          <div class="theater-complex-create-info-address">Address:</div>
          <div class="flex-display margin-top">
            <input class="theater-complex-create-info-address-field form-control" name="street" value="<?php echo $complex_info["street"]; ?>" placeholder="Street" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-create-info-address-field form-control" name="town" value="<?php echo $complex_info["town"]; ?>" placeholder="Town/City" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-create-info-address-field form-control" name="province" value="<?php echo $complex_info["province"]; ?>" placeholder="State/Province" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-create-info-address-field form-control" name="country" value="<?php echo $complex_info["country"]; ?>" placeholder="Country" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-create-info-address-field form-control" name="postalcode" value="<?php echo $complex_info["postalcode"]; ?>" placeholder="Postal Code" type="text" />
          </div>
          <div class="theater-complex-create-button-container">
            <input name="complex-edit-button" type="submit" class="btn btn-success" value="Edit Complex" />
          </div>
        </form>
        <?php } ?>
      </div>
      <div class="theater-complex-edit-section">
        <?php
          if (!$complex_edit) { 
            if ($_SESSION["user_role"] == 1) {
        ?>
          <button id="theater-complex-edit-button" type="button" class="btn btn-primary" onclick="location.href = '/CISC332/theater-complex-details/?edit_id=<?php echo $complex_info["id"]; ?>'">Edit</button>
        <?php
            }
          }
        ?>
      </div>
    </main>
  </body>
</html>