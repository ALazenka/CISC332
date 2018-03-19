<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Movie - OMTS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="movie.css" />
    <script src="movie.js"></script>
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
    <main class="movie-content">


  <?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "cisc332";
  $movie_id = "";
  $movie_info = "";
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'id=') !== false) {
      $movie_id = str_replace("id=", "", $item);
    } 
  };

  $get_movie_info = "SELECT id, title, run_time, rating, synopsis, director, production_company, supplier_name, start_date, end_date FROM movie WHERE id='" . $movie_id . "'";
  $result = $conn->query($get_movie_info);
  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  $conn->close();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $movie_info = $row;
    }
  ?>
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="movie-picture-section">
        <div class="movie-default-image"><i class="fas fa-film" id="movie-image" title="Movie"></i></div>
        <div class="movie-account-number"><?php echo $movie_info["id"]; ?></div>
      </div>
      <div class="movie-info-section">
        <div class="movie-info-title">
          <div class="movie-info-title-badge">Title:</div>
          <div class="movie-info-title-field"><?php echo $movie_info["title"]; ?></div>
        </div>
        <div class="movie-info-run-time">
          <div class="movie-info-run-time-badge">Run Time:</div>
          <div class="movie-info-run-time-field"><?php echo $movie_info["run_time"]; ?></div>
        </div>
        <div class="movie-info-rating">
          <div class="movie-info-rating-badge">Rating:</div>
          <div class="movie-info-rating-field"><?php echo $movie_info["rating"]; ?></div>
        </div>
        <div class="movie-info-synopsis">
          <div class="movie-info-synopsis-badge">Synopsis:</div>
          <div class="movie-info-synopsis-field"><?php echo $movie_info["synopsis"]; ?></div>
        </div>
        <div class="movie-info-director">
          <div class="movie-info-director-badge">Director:</div>
          <div class="movie-info-director-field"><?php echo $movie_info["director"]; ?></div>
        </div>
        <div class="movie-info-production-company">
          <div class="movie-info-production-company-badge">Production Company:</div>
          <div class="movie-info-production-company-field"><?php echo $movie_info["production_company"]; ?></div>
        </div>
        <div class="movie-info-supplier-name">
          <div class="movie-info-supplier-name-badge">Supplier Name:</div>
          <div class="movie-info-supplier-name-field"><?php echo $movie_info["supplier_name"]; ?></div>
        </div>
        <div class="movie-info-start-date">
          <div class="movie-info-start-date-badge">Start Date:</div>
          <div class="movie-info-start-date-field"><?php echo $movie_info["start_date"]; ?></div>
        </div>
        <div class="movie-info-end-date">
          <div class="movie-info-end-date-badge">End Date:</div>
          <div class="movie-info-end-date-field"><?php echo $movie_info["end_date"]; ?></div>
        </div>
      </div>
      <div class="movie-edit-section">
        <button id="movie-edit-button" type="button" class="btn btn-secondary">Edit</button>
      </div>
    <?php
    } else {
      ?>
      <div>No movies were found with ID <?php echo $movie_id; ?></div>
      <?php
    }
    ?>
    </main>
  </body>
</html>