<?php
  include '../components/sql-connect.php';
  session_start();

  $tickets = [];
  $get_tickets = "SELECT * FROM reservation JOIN showing ON showing.id=reservation.showing_id JOIN movie on movie.id=showing.movie_id WHERE reservation.account_number=" . $_SESSION["account_number"];
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
        <h1 class="tickets-title">Tickets</h1>
      </div>
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
            <tr class="table-item" onclick="location.href = '/CISC332/ticket-details/?id=<?php echo $ticket['id']; ?>';">
              <th scope="row"><?php echo $ticket['id']; ?></th>
              <td><?php echo $ticket['title']; ?></td>
              <td><?php echo $ticket['tickets_reserved']; ?></td>
              <td><?php echo $ticket['start_time']; ?></td>
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