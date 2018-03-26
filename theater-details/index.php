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

  $theater_info = "";
  $theater_complexes = [];
  $theater_id = 0;
  $theater_edit = false;
  $screen_sizes = ['small', 'medium', 'large'];
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'theater_id=') !== false) {
      $theater_id = str_replace("theater_id=", "", $item);
    } else if(strpos($item, 'edit_id=') !== false) {
      $theater_id = str_replace("edit_id=", "", $item);
      $_SESSION["edit_theater_id"] = $theater_id;
      $theater_edit = true;
    }
  };
  
  if (isset($_POST["theater-edit-button"])) {
    $get_theater = "SELECT theater.id, theater.theater_number, theater.screen_size, theater_complex.name as complex_name, theater_complex.id as complex_id
                    FROM theater
                    JOIN theater_complex
                      ON theater.theater_complex_id=theater_complex.id
                    WHERE theater.id=" . $_SESSION["edit_theater_id"];
    $result = $conn->query($get_theater);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $theater_info = $row;
      }
    }
    $complex_id = $_POST["complex_id"];
    $theater_number = $_POST["theater_number"];
    $screen_size = $_POST["screen_size"];
    $theater_id = $_SESSION["edit_theater_id"];
    $max_seats = $_POST["max_seats"];

    $duplicate_theater = "SELECT *
                          FROM theater
                          JOIN theater_complex
                            ON theater.theater_complex_id=theater_complex.id
                          WHERE theater_complex.id=$complex_id";
    $result = $conn->query($duplicate_theater);
    $theater_numbers = [];
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        array_push($theater_numbers, $row["theater_number"]);
      }
    }
    if ($theater_info["screen_size"] != $screen_size) {
      $update_in_complex = "UPDATE theater SET screen_size = '$screen_size' WHERE id = '$theater_id'";
      $conn->query($update_in_complex);
    }
    if ($theater_info["max_seats"] != $max_seats) {
      $update_in_complex = "UPDATE theater SET max_seats = '$max_seats' WHERE id = '$theater_id'";
      $conn->query($update_in_complex);
    }
    if ($theater_info["theater_number"] == $theater_number && $theater_info["complex_id"] == $complex_id) {
      $_SESSION["theater_edit_success"] = true;
      header("Location: ../theater-complex");
    } else {
      if (!in_array($theater_number, $theater_numbers)) {
        $update_in_complex = "UPDATE theater SET theater_complex_id = '$complex_id', theater_number = '$theater_number', screen_size = '$screen_size' WHERE id = '$theater_id'";
        $conn->query($update_in_complex);
        $_SESSION["theater_edit_success"] = true;
        header("Location: ../theater-complex");
      } else {
        $_SESSION["duplicate_theater_error"] = true;
        header("Location: ../theater-details/?edit_id=$theater_id");
      }
    }
  }

  $get_theater = "SELECT theater.id, theater.theater_number, theater.screen_size, theater_complex.name as complex_name, theater_complex.id as complex_id, theater.max_seats
                  FROM theater
                  JOIN theater_complex
                    ON theater.theater_complex_id=theater_complex.id
                  WHERE theater.id=$theater_id";
  $result = $conn->query($get_theater);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $theater_info = $row;
    }
  }

  $get_complexes = "SELECT id, name, street, town, province, country, phone_number
            FROM theater_complex";
  $result = $conn->query($get_complexes);
  $conn->close();
  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
  }
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($theater_complexes, $row);
    }
  }
?>
<html>
  <head>
    <title>Theater Details - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="theater-details.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="theater-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="theater-picture-section">
        <div class="theater-default-image"><i class="fas fa-tv" id="theater-image" title="Theater"></i></div>
        <div class="theater-account-number"><?php echo $theater_info["id"]; ?></div>
      </div>
      <div class="theater-info-section">
        <?php if (!$theater_edit) { ?>
        <div class="theater-info-theater-complex">
          <div class="theater-info-theater-complex-badge">Theater Complex:</div>
          <div class="theater-info-theater-complex-field"><?php echo $theater_info["complex_name"]; ?></div>
        </div>
        <div class="theater-info-theater-number">
          <div class="theater-info-theater-number-badge">Theater Number:</div>
          <div><?php echo $theater_info["theater_number"]; ?></div>
        </div>
        <div class="theater-info-screen-size">
          <div class="theater-info-screen-size-badge">Screen Size:</div>
          <div><?php echo $theater_info["screen_size"]; ?></div>
        </div>
        <div class="theater-info-max-seats">
          <div class="theater-info-max-seats-badge">Number of Seats:</div>
          <div><?php echo $theater_info["max_seats"]; ?></div>
        </div>
        <?php } else { ?>
        <form action="index.php" method="POST">
          <div class="theater-edit-info-theater-complex">
            <div class="theater-edit-info-theater-complex-badge">Theater Complex:</div>
            <select name="complex_id" class="custom-select" id="inputGroupSelect01">
              <?php
                foreach ($theater_complexes as &$complex) {
                  if ($theater_info["complex_id"] == $complex["id"]) {
              ?>
                <option value="<?php echo $complex["id"]; ?>" selected><?php echo $complex["name"]; ?></option>
              <?php
                  } else {
              ?>
                <option value="<?php echo $complex["id"]; ?>"><?php echo $complex["name"]; ?></option>
              <?php
                  }
                }
              ?>
            </select>
          </div>
          <div class="theater-edit-info-theater-number">
            <div class="theater-edit-info-theater-number-badge">Theater Number:</div>
            <input type="text" class="form-control" name="theater_number" value="<?php echo $theater_info["theater_number"]; ?>" placeholder="Phone Number" />
          </div>
          <?php
            if (isset($_SESSION["duplicate_theater_error"]) && $_SESSION["duplicate_theater_error"]) {
              $_SESSION["duplicate_theater_error"] = false;
          ?>
          <div class="theater-edit-info-theater-number" style="color:red;text-align:right;">This complex already has that theater number in it!</div>
          <?php
            }
          ?>
          <div class="theater-edit-info-screen-size">
            <div class="theater-edit-info-screen-size-badge">Screen Size:</div>
            <select name="screen_size" class="custom-select" id="inputGroupSelect01">
              <?php
                foreach ($screen_sizes as &$size) {
                  if ($theater_info["screen_size"] == $size) {
              ?>
                <option value="<?php echo $size; ?>" selected><?php echo $size; ?></option>
              <?php
                  } else {
              ?>
                <option value="<?php echo $size; ?>"><?php echo $size; ?></option>
              <?php
                  }
                }
              ?>
            </select>
          </div>
          <div class="theater-edit-info-max-seats">
            <div class="theater-edit-info-max-seats-badge">Number of Seats:</div>
            <input type="text" class="form-control" name="max_seats" value="<?php echo $theater_info["max_seats"]; ?>" placeholder="Phone Number" />
          </div>
          <div class="theater-edit-button-container">
            <input name="theater-edit-button" type="submit" class="btn btn-success" value="Edit Theater" />
          </div>
        </form>
        <?php } ?>
      </div>
      <div class="theater-edit-section">
        <?php
          if (!$theater_edit) { 
            if ($_SESSION["user_role"] == 1) {
        ?>
          <button id="theater-edit-button" type="button" class="btn btn-primary" onclick="location.href = '/CISC332/theater-details/?edit_id=<?php echo $theater_info["id"]; ?>'">Edit</button>
        <?php
            }
          }
        ?>
      </div>
    </main>
  </body>
</html>