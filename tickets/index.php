<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cisc332";
$tickets = [];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
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
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tickets - OMTS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="tickets.css" />
    <script src="tickets.js"></script>
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