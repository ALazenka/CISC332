<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"])) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  $remove_id = 0;
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'remove_id=') !== false) {
      $remove_id = str_replace("remove_id=", "", $item);
      
      $find_reservation = "SELECT account_number from reservation WHERE id=$remove_id";
      $result = $conn->query($find_reservation);
      $reservation_an = 0;
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $reservation_an = $row;
        }
      }

      if ($remove_id > 0 && $_SESSION["account_number"] == $reservation_an["account_number"]) {
        $remove_reservation_query = "DELETE FROM reservation WHERE id=$remove_id";
        $conn->query($remove_reservation_query);
        $_SESSION["remove_reservation_success"] = true;
      }
    } 
  };

  $tickets = [];
  $get_tickets = "SELECT reservation.id as id, movie.title as movie_title, reservation.tickets_reserved, showing.start_time, theater_complex.name as complex_name
                  FROM reservation
                  JOIN showing
                    ON showing.id=reservation.showing_id
                  JOIN movie
                    ON movie.id=showing.movie_id
                  JOIN theater_complex
                    ON showing.theater_complex_id=theater_complex.id
                  WHERE reservation.account_number=" . $_SESSION["account_number"];
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
  $conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Tickets - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="tickets.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="tickets-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="tickets-title-section">
        <h1 class="tickets-title">Tickets
        <a class="btn btn-success buy-tickets-button" href="/CISC332/reservation-complex" role="button">Buy Tickets</a></h1>
        <h4>Purchased Tickets for Upcoming Shows</h4>
      </div>
      <?php
        if (isset($_SESSION["remove_reservation_success"]) && $_SESSION["remove_reservation_success"]) {
          $_SESSION["remove_reservation_success"] = false;
      ?>
        <div class="alert alert-success tickets-alert-section" role="alert">
          <strong>Tickets Removed!</strong> You can book another reservation from the side menu.
        </div>
      <?php
        }
      ?>
      <div class="tickets-info-section">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Movie Name</th>
              <th scope="col">Theater Complex Name</th>
              <th scope="col">Tickets Reserved</th>
              <th scope="col">Start Time</th>
              <th scope="col">Cancel Tickets</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($tickets as &$ticket) {
                
            ?>
            <tr class="table-item">
              <td><?php echo $ticket['movie_title']; ?></td>
              <td><?php echo $ticket['complex_name']; ?></td>
              <td><?php echo $ticket['tickets_reserved']; ?></td>
              <td><?php echo $ticket['start_time']; ?></td>
              <td class="remove-cell">
                <a class="cancel-tickets-link" href="/CISC332/tickets/?remove_id=<?php echo $ticket["id"]; ?>">
                  <i class="far fa-trash-alt cancel-tickets"></i>
                </a>
              </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
        </table>
      </div>
    </main>
  </body>
</html>