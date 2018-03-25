<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"])) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  $showtimes = [];
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  $complex_id = 0;
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'id=') !== false) {
      $complex_id = str_replace("complex_id=", "", $item);
    } 
  };
  $get_showtimes = "SELECT showing.id as id, movie.title as movie_title, theater_complex.name as complex_name, theater.theater_number, showing.start_time, movie.run_time
            FROM showing 
            JOIN theater_complex 
              ON theater_complex.id=showing.theater_complex_id 
            JOIN theater 
              ON showing.theater_id=theater.id
            JOIN movie
              ON showing.movie_id=movie.id
            WHERE theater_complex_id=$complex_id";
  $result = $conn->query($get_showtimes);

  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($showtimes, $row);
    }
  }
  $conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Reservation Showtimes - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="showtimes.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="showtimes-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="showtimes-title-section">
        <h1 class="showtimes-title">Reservation - Showtimes</h1>
        <h4>Select a Showtime to Purchase Tickets</h4>
      </div>
      <div class="showtimes-info-section">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Movie Title</th>
              <th scope="col">Theater Complex</th>
              <th scope="col">Theater Number</th>
              <th scope="col">Start Time</th>
              <th scope="col">Run Time (Minutes)</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($showtimes as &$showtime) {
            ?>
            <tr class="table-item" onclick="location.href = '/CISC332/checkout/?showtime_id=<?php echo $showtime['id']; ?>';">
              <th scope="row"><?php echo $showtime['id']; ?></th>
              <td><?php echo $showtime['movie_title']; ?></td>
              <td><?php echo $showtime['complex_name']; ?></td>
              <td><?php echo $showtime['theater_number']; ?></td>
              <td><?php echo $showtime['start_time']; ?></td>
              <td><?php echo $showtime['run_time']; ?></td>
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