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
  
  if (isset($_POST["theater-create-button"])) {
    $complex_id = $_POST["complex_id"];
    $theater_number = $_POST["theater_number"];
    $screen_size = $_POST["screen_size"];
    $max_seats = $_POST["max_seats"];
    $theater_info = "";
    $get_theater = "SELECT theater.id, theater.theater_number, theater.screen_size, theater_complex.name as complex_name, theater_complex.id as complex_id
                    FROM theater
                    JOIN theater_complex
                      ON theater.theater_complex_id=theater_complex.id
                    WHERE theater.id=" . $theater_id;
    $result = $conn->query($get_theater);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $theater_info = $row;
      }
    }

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
    if (!in_array($theater_number, $theater_numbers)) {
      $create_theater = "INSERT INTO theater (`theater_complex_id`, `theater_number`, `screen_size`, `max_seats`, `id`)
                        VALUES ('$complex_id', '$theater_number', '$screen_size', '$max_seats', '')";
      $conn->query($create_theater);
      $_SESSION["theater_create_success"] = true;
      header("Location: ../theater-complex");
    } else {
      $_SESSION["duplicate_theater_error"] = true;
      header("Location: ../theater-details/?edit_id=$theater_id");
    }
  }

  $get_theater = "SELECT theater.id, theater.theater_number, theater.screen_size, theater_complex.name as complex_name, theater_complex.id as complex_id
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

  $theater_complexes = [];
  $screen_sizes = ['small', 'medium', 'large'];
  $get_complexes = "SELECT id, name, street, town, province, country, phone_number
                    FROM theater_complex
                    ORDER BY name";
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
    <title>Theater Create - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="theater-create.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="theater-create-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="theater-create-picture-section">
        <div class="theater-create-default-image"><i class="far fa-star" id="theater-create-image" title="Review"></i></div>
        <div class="theater-create-theater-number">Create Complex</div>
      </div>
      <div class="theater-create-info-section">
        <form action="index.php" method="POST">
          <div class="theater-create-info-theater-complex">
            <div class="theater-create-info-theater-complex-badge">Theater Complex:</div>
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
          <div class="theater-create-info-theater-number">
            <input type="text" class="form-control" name="theater_number" value="<?php echo $theater_info["theater_number"]; ?>" placeholder="Theater Number" />
          </div>
          <?php
            if (isset($_SESSION["duplicate_theater_error"]) && $_SESSION["duplicate_theater_error"]) {
              $_SESSION["duplicate_theater_error"] = false;
          ?>
          <div class="theater-create-info-theater-number" style="color:red;text-align:right;">This complex already has that theater number in it!</div>
          <?php
            }
          ?>
          <div class="theater-create-info-screen-size">
            <div class="theater-create-info-screen-size-badge">Screen Size:</div>
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
          <div class="theater-create-info-seats">
            <input type="text" class="form-control" name="max_seats" placeholder="Number of Seats" />
          </div>
          <div class="theater-create-button-container">
            <input name="theater-create-button" type="submit" class="btn btn-success" value="Create Theater" />
          </div>
        </form>
      </div>
    </main>
  </body>
</html>