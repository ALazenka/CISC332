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

  $showings = [];
  $get_showings = "SELECT showing.id as id, movie.title as movie_title, theater_complex.name as complex_name, theater.theater_number, showing.start_time, movie.run_time
                    FROM showing 
                    JOIN theater_complex 
                      ON theater_complex.id=showing.theater_complex_id 
                    JOIN theater 
                      ON showing.theater_id=theater.id
                    JOIN movie
                      ON showing.movie_id=movie.id
                    ORDER BY complex_name, theater_number";
  $result = $conn->query($get_showings);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($showings, $row);
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Showings - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="showings.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="showings-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="showings-title-section">
        <h1 class="showings-title">Showings
          <?php if ($_SESSION["user_role"] == 1) { ?>
          <a class="btn btn-success add-showing-button" href="/CISC332/showing-create" role="button">Add Showing</a>
          <?php } ?>
        </h1>
        <h4>Manage Where Movies Can Be Viewed In Theaters</h4>
      </div>
      <?php
        if (isset($_SESSION["showing_create_success"]) && $_SESSION["showing_create_success"]) {
          $_SESSION["showing_create_success"] = false;
      ?>
      <div class="alert alert-success showings-alert-section" role="alert">
        <strong>Showing Created!</strong>
      </div>
      <?php
        }
      ?>
      <?php
        if (isset($_SESSION["showing_edit_success"]) && $_SESSION["showing_edit_success"]) {
          $_SESSION["showing_edit_success"] = false;
      ?>
      <div class="alert alert-success showings-alert-section" role="alert">
        <strong>Showing Updated!</strong>
      </div>
      <?php
        }
      ?>
      <div class="showings-info-section">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">Theater Complex</th>
              <th scope="col">Theater Number</th>
              <th scope="col">Movie Title</th>
              <th scope="col">Start Time</th>
              <th scope="col">Run Time (Minutes)</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($showings as &$showing) {
            ?>
            <tr class="table-item" onclick="location.href = '/CISC332/showing-details/?showing_id=<?php echo $showing['id']; ?>';">
              <td><?php echo $showing['complex_name']; ?></td>
              <td><?php echo $showing['theater_number']; ?></td>
              <td><?php echo $showing['movie_title']; ?></td>
              <td><?php echo $showing['start_time']; ?></td>
              <td><?php echo $showing['run_time']; ?></td>
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