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
  
  if (isset($_POST["theater-complex-edit-button"])) {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $street = $_POST["street"];
    $town = $_POST["town"];
    $province = $_POST["province"];
    $country = $_POST["country"];
    $postalcode = $_POST["postalcode"];
    $create_complex = "INSERT INTO theater_complex (`name`, `phone_number`, `street`, `town`, `province`, `country`, `postalcode`)
                      VALUES ('$name', '$phone', '$street', '$town', '$province', '$country', '$postalcode')";
    $conn->query($create_complex);
    $_SESSION["complex_create_success"] = true;
    header("Location: ../theater-complex");
  }

  $get_complex_info = "SELECT * FROM theater_complex";
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
        <div class="theater-complex-default-image"><i class="far fa-star" id="theater-complex-image" title="Complex"></i></div>
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
            <input type="text" class="form-control" name="name" placeholder="Complex Name" />
          </div>
          <div class="theater-complex-create-info-phone">
            <input type="text" class="form-control" name="phone" placeholder="Phone Number" />
          </div>
          <div class="theater-complex-info-address">Address:</div>
          <div class="flex-display margin-top">
            <input class="theater-complex-info-name-field form-control" name="street" placeholder="Street" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-info-name-field form-control" name="town" placeholder="Town/City" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-info-name-field form-control" name="province" placeholder="State/Province" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-info-name-field form-control" name="country" placeholder="Country" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-info-name-field form-control" name="postalcode" placeholder="Postal Code" type="text" />
          </div>
          <div class="theater-complex-create-button-container">
            <input name="theater-complex-create-button" type="submit" class="btn btn-success" value="Create Complex" />
          </div>
        </form>
        <?php } ?>
      </div>
      <div class="theater-complex-edit-section">
        <?php
          if ($_SESSION["user_role"] == 1) {
        ?>
          <button id="theater-complex-edit-button" type="button" class="btn btn-primary" onclick="location.href = '/CISC332/theater-complex-details/?edit_id=<?php echo $complex_info["id"]; ?>'">Edit</button>
        <?php
          }
        ?>
      </div>
    </main>
  </body>
</html>