<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"])) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  $showtime_info = "";
  $showing_tickets = "";
  $over_ticket_count = false;
  $ticket_override = false;
  $showtime_id = 0;
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'showtime_id=') !== false) {
      $showtime_id = str_replace("showtime_id=", "", $item);
      $_SESSION["showtime_id"] = $showtime_id;
    } 
  };

  if (isset($_POST["checkout-complete"])) {
    $showtime_id = $_SESSION["showtime_id"];
    $check_ticket_limit = "SELECT showing.id, showing.seats_available - sum(reservation.tickets_reserved) as tickets_available, showing.seats_available
                          FROM showing
                          JOIN
                            reservation
                          ON
                            showing.id=reservation.showing_id
                          WHERE showing.id='$showtime_id' GROUP BY showing.id";
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
      $create_reservation = "INSERT INTO `Reservation` (`account_number`, `showing_id`, `tickets_reserved`, `id`) VALUES ('$account_number', '$showtime_id', '$tickets_wanted', '')";
      $conn->query($create_reservation);
      $_SESSION["reservation_success"] = true;
      header("Location: ../reservation-complex");
    } else {
      $over_ticket_count = true;
    }
  }

  $get_showtime_info = "SELECT
                        showing.id as id,
                        movie.title as movie_title,
                        theater_complex.name as complex_name,
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
                      WHERE showing.id=" . $showtime_id;

  $result = $conn->query($get_showtime_info);
  $conn->close();
  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $showtime_info = $row;
    }
  }
?>
<html>
  <head>
    <title>Checkout - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="checkout.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="checkout-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="checkout-picture-section">
        <div class="checkout-default-image"><i class="fas fa-video" id="checkout-image" title="Checkout"></i></div>
        <div class="checkout-account-number">Reservation Checkout</div>
      </div>
      <div class="checkout-info-section">
        <form action="index.php" method="POST">
          <div class="checkout-info-movie-title">
            <div class="checkout-info-movie-title-badge">Movie Title:</div>
            <div class="checkout-info-movie-title-field"><?php echo $showtime_info["movie_title"]; ?></div>
          </div>
          <div class="checkout-info-start-time">
            <div class="checkout-info-start-time-badge">Start Time:</div>
            <div class="checkout-info-start-time-field"><?php echo $showtime_info["start_time"]; ?></div>
          </div>
          <div class="checkout-info-complex">
            <div class="checkout-info-complex-badge">Theater Complex:</div>
            <div class="checkout-info-complex-field"><?php echo $showtime_info["complex_name"]; ?></div>
          </div>
          <div class="checkout-info-complex-address">
            <div class="checkout-info-complex-address-badge">Theater Complex Address:</div>
            <div class="checkout-info-complex-address-field"><?php echo $showtime_info['street'] . ' ' . $showtime_info['town'] . ', ' . $showtime_info['province'] . ', ' . $showtime_info['country']; ?></div>
          </div>
          <div class="checkout-info-theater-number">
            <div class="checkout-info-theater-number-badge">Theater Number:</div>
            <div class="checkout-info-theater-number-field"><?php echo $showtime_info["theater_number"]; ?></div>
          </div>
          <div class="checkout-info-tickets">
            <div class="checkout-info-tickets-badge">Ticket(s):</div>
            <select name="tickets_wanted" class="custom-select tickets-dropdown" id="inputGroupSelect01">
              <option value="1" selected>1</option>
              <?php
                foreach (range(2, 10) as $number) {
              ?>
              <option value="<?php echo $number; ?>"><?php echo $number; ?></option>
              <?php
                }
              ?>
            </select>
          </div>
          <div class="checkout-button">
            <input id="checkout-complete-button" name="checkout-complete" type="submit" class="btn btn-success" value="Reserve Tickets" />
          </div>
        </form>
      </div>
    </main>
  </body>
</html>