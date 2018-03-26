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

  $showing_info = "";
  $showing_tickets = "";
  $over_ticket_count = false;
  $ticket_override = false;
  $showing_id = 0;
  $showing_edit = false;
  $movie_list = [];
  $theater_complex_list = [];
  $theater_list = [];
  $complex_id = 0;
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'showing_id=') !== false) {
      $showing_id = str_replace("showing_id=", "", $item);
      $_SESSION["showing_id"] = $showing_id;
    } else if (strpos($item, 'edit_id=') !== false) {
      $showing_id = str_replace("edit_id=", "", $item);
      $_SESSION["edit_showing_id"] = $showing_id;
      $showing_edit = true;
    }
  };

  $get_showing_info = "SELECT
                        showing.id as id,
                        movie.title as movie_title,
                        theater_complex.name as complex_name,
                        theater_complex.id as complex_id,
                        theater_complex.street,
                        theater_complex.town,
                        theater_complex.province,
                        theater_complex.country,
                        theater_complex.postalcode,
                        theater.theater_number,
                        showing.start_time,
                        movie.run_time
                      FROM showing 
                      JOIN theater_complex 
                        ON theater_complex.id=showing.theater_complex_id 
                      JOIN theater 
                        ON showing.theater_id=theater.id
                      JOIN movie
                        ON showing.movie_id=movie.id
                      WHERE showing.id=" . $showing_id;

  $result = $conn->query($get_showing_info);
  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $showing_info = $row;
    }
  }

  if ($showing_edit) {

    $get_complexes = "SELECT * FROM theater_complex";
    $result = $conn->query($get_complexes);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        array_push($theater_complex_list, $row);
      }
    }


    $get_movies = "SELECT * FROM movie";
    $result = $conn->query($get_movies);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        array_push($movie_list, $row);
      }
    }

    $get_theaters = "SELECT * FROM theater where theater_complex_id = " . $showing_info["complex_id"];
    $result = $conn->query($get_theaters);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        array_push($theater_list, $row);
      }
    }
  }

  if (isset($_POST["checkout-complete"])) {
    $showing_id = $_SESSION["showing_id"];
    $check_ticket_limit = "SELECT showing.id, showing.seats_available - sum(reservation.tickets_reserved) as tickets_available, showing.seats_available
                          FROM showing
                          JOIN
                            reservation
                          ON
                            showing.id=reservation.showing_id
                          WHERE showing.id='$showing_id' GROUP BY showing.id";
    $result = $conn->query($check_ticket_limit);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $showing_tickets = $row;
      }
    } else {
      $ticket_override = true;
    }

    if ($tickets_available - $_POST["tickets_wanted"] >= 0 || $ticket_override) {
      $account_number = $_SESSION["account_number"];
      $tickets_wanted = $_POST["tickets_wanted"];
      $create_reservation = "INSERT INTO `Reservation` (`account_number`, `showing_id`, `tickets_reserved`, `id`) VALUES ('$account_number', '$showing_id', '$tickets_wanted', '')";
      $conn->query($create_reservation);
      $_SESSION["reservation_success"] = true;
      header("Location: ../reservation-complex");
    } else {
      $over_ticket_count = true;
    }
  }
  $conn->close();
?>
<html>
  <head>
    <title>Showing Details - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="showing-details.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="showing-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="showing-picture-section">
        <div class="showing-default-image"><i class="fas fa-list" id="showing-image" title="Showing"></i></div>
        <div class="showing-account-number">Showing Details</div>
      </div>
      <div class="showing-info-section">
        <?php if (!$showing_edit) { ?>
        <div class="showing-info-complex">
          <div class="showing-info-complex-badge">Theater Complex:</div>
          <div class="showing-info-complex-field"><?php echo $showing_info["complex_name"]; ?></div>
        </div>
        <div class="showing-info-theater-number">
          <div class="showing-info-theater-number-badge">Theater Number:</div>
          <div class="showing-info-theater-number-field"><?php echo $showing_info["theater_number"]; ?></div>
        </div>
        <div class="showing-info-movie-title">
          <div class="showing-info-movie-title-badge">Movie Title:</div>
          <div class="showing-info-movie-title-field"><?php echo $showing_info["movie_title"]; ?></div>
        </div>
        <div class="showing-info-complex-address">
          <div class="showing-info-complex-address-badge">Theater Complex Address:</div>
          <div class="showing-info-complex-address-field"><?php echo $showing_info['street'] . ' ' . $showing_info['town'] . ', ' . $showing_info['province'] . ', ' . $showing_info['country']; ?></div>
        </div>
        <div class="showing-info-start-time">
          <div class="showing-info-start-time-badge">Start Time:</div>
          <div class="showing-info-start-time-field"><?php echo $showing_info["start_time"]; ?></div>
        </div>
        <?php } else { ?>
        <form action="index.php" method="POST">
          <div class="showing-edit-info-complex">
            <div class="showing-edit-info-complex-badge">Theater Complex:</div>
            <select name="theater_complex_id" class="custom-select" onchange="changeComplex(this.value)">
              <?php
                foreach ($theater_complex_list as &$complex) {
                  if ($complex["id"] == $complex_id) {
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
          <div class="showing-edit-info-theater-number">
            <div class="showing-edit-info-theater-number-badge">Theater Number:</div>
            <select name="theater_id" class="custom-select">
              <?php
                  foreach ($theater_list as &$theater) {
              ?>
              <option value="<?php echo $theater["id"]; ?>"><?php echo $theater["theater_number"]; ?></option>
              <?php
                  }
              ?>
            </select>
          </div>
          <div class="showing-edit-info-movie">
            <div class="showing-edit-info-movie-badge">Movie:</div>
            <select name="movie_id" class="custom-select">
              <?php
                  foreach ($movie_list as &$movie) {
              ?>
              <option value="<?php echo $movie["id"]; ?>"><?php echo $movie["title"]; ?></option>
              <?php
                  }
              ?>
            </select>
          </div>
          <div class="showing-edit-info-start-time">
            <input type="text" class="form-control" name="start_time" value="<?php echo $showing_info["start_time"]; ?>" placeholder="Start Time" />
          </div>
          <div class="showing-edit-button-container">
            <input name="showing-edit-button" type="submit" class="btn btn-success" value="Edit Showing" />
          </div>
          <?php } ?>
        </form>
      </div>
      <?php if (!$showing_edit) { ?>
      <div class="showing-edit-section">
        <button id="showing-edit-button" type="button" class="btn btn-primary" onclick="location.href = '/CISC332/showing-details/?edit_id=<?php echo $showing_info['id']; ?>'">Edit Showing</button>
      </div>
      <?php } ?>
    </main>
  </body>
</html>