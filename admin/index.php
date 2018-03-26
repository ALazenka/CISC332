<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"]) || $_SESSION["account_number"] < 0) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] != 1) {
    $_SESSION["user_role_redirect"] = true;
    header("Location: ../reservation-complex");
  }

  $popular_movie = "";
  $popular_theater = "";
  $tickets_sold = "SELECT showing.id, sum(reservation.tickets_reserved) as tickets_purchased, movie.title
                        FROM showing
                        JOIN reservation
                          ON showing.id=reservation.showing_id
                        JOIN movie
                          ON showing.movie_id=movie.id
                        GROUP BY showing.id
                        ORDER BY tickets_purchased DESC";
  $result = $conn->query($tickets_sold);

  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }

  if ($result->num_rows > 0) {
    $loop_count = 0;
    while($loop_count != 1 && $row = $result->fetch_assoc()) {
      $popular_movie = $row;
      $loop_count++;
    }
  }

  $popular_theater = "SELECT theater_complex.name, theater_complex.id as complex_id, sum(reservation.tickets_reserved) as tickets_purchased, movie.title
                      FROM showing
                      JOIN reservation
                        ON showing.id=reservation.showing_id
                      JOIN movie
                        ON showing.movie_id=movie.id
                      JOIN theater_complex
                        ON showing.theater_complex_id=theater_complex.id
                      GROUP BY complex_id
                      ORDER BY tickets_purchased DESC";
  $result = $conn->query($popular_theater);

  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }

  if ($result->num_rows > 0) {
    $loop_count = 0;
    while($loop_count != 1 && $row = $result->fetch_assoc()) {
      $popular_theater = $row;
      $loop_count++;
    }
  }
  $conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Admin - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="admin.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="admin-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="admin-title-section">
        <h1 class="admin-title">Admin Stats</h1>
        <h4>Movies Currently Playing in Theaters</h4>
      </div>
      <div class="admin-info-section">
          <div class="admin-info-popular-movie">
            <div class="admin-info-popular-movie-badge">Most Popular Movie:</div>
            <div><?php echo $popular_movie["title"] ?></div>
            <div class="admin-info-popular-movie-badge">Tickets Sold:</div>
            <div><?php echo $popular_movie["tickets_purchased"] ?></div>
          </div>
          <div class="admin-info-popular-theater">
            <div class="admin-info-popular-theater-badge">Most Popular Theater Complex:</div>
            <div><?php echo $popular_theater["name"] ?></div>
            <div class="admin-info-popular-theater-badge">Tickets Sold:</div>
            <div><?php echo $popular_theater["tickets_purchased"] ?></div>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>