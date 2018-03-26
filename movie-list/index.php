<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"]) || $_SESSION["account_number"] < 0) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  $movie_list = [];
  $get_movies = "SELECT id, title, run_time, rating  FROM movie";
  $result = $conn->query($get_movies);

  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($movie_list, $row);
    }
  }
  $conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Movie List - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="movie-list.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="movie-list-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="movie-list-title-section">
        <h1 class="movie-list-title">Movie List
          <?php if ($_SESSION["user_role"] == 1) { ?>
          <a class="btn btn-success add-movie-button" href="/CISC332/movie-create" role="button">Add Movie</a>
          <?php } ?>
        </h1>
        <h4>Movies Currently Playing in Theaters</h4>
      </div>
      <?php
        if (isset($_SESSION["movie_create_success"]) && $_SESSION["movie_create_success"]) {
          $_SESSION["movie_create_success"] = false;
      ?>
      <div class="alert alert-success movie-list-alert-section" role="alert">
        <strong>Movie Created!</strong>
      </div>
      <?php
        }
      ?>
      <div class="movie-list-info-section">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">Title</th>
              <th scope="col">Run Time (Minutes)</th>
              <th scope="col">Rating</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($movie_list as &$movie) {
            ?>
            <tr class="table-item" onclick="location.href = '/CISC332/movie/?movie_id=<?php echo $movie['id']; ?>';">
              <td><?php echo $movie['title']; ?></td>
              <td><?php echo $movie['run_time']; ?></td>
              <td><?php echo $movie['rating']; ?></td>
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