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

  $movie_id = 0;
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'movie_id=') !== false) {
      $movie_id = str_replace("movie_id=", "", $item);
    } 
  };
  
  if (isset($_POST["movie-create-button"])) {
    $movie_title = $_POST["movie_title"];
    $movie_synopsis = $_POST["movie_synopsis"];
    $run_time = $_POST["run_time"];
    $rating = $_POST["rating"];
    $director = $_POST["director"];
    $production_company = $_POST["production_company"];
    $supplier = $_POST["supplier"];
    $start_date = $_POST["start_date_date"];
    $start_date .= $_POST["start_date_month"];
    $start_date .= $_POST["start_date_year"];
    $end_date = $_POST["end_date_date"];
    $end_date .= $_POST["end_date_month"];
    $end_date .= $_POST["end_date_year"];
    $create_movie = "INSERT INTO movie (`title`, `run_time`, `rating`, `synopsis`, `director`, `production_company`, `supplier_name`, `start_date`, `end_date`, `id`)
                      VALUES ('$movie_title', '$run_time', '$rating', '$movie_synopsis', '$director', '$production_company', '$supplier', '$start_date', '$end_date', '')";
    $conn->query($create_movie);
    $_SESSION["movie_create_success"] = true;
    header("Location: ../movie-list");
  }

  $ratings = ["PG", "PG-13", "R", "NC-17"];
  $conn->close();
?>
<html>
  <head>
    <title>Movie Create - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="movie-create.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="movie-create-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="movie-create-picture-section">
        <div class="movie-create-default-image"><i class="far fa-star" id="movie-create-image" title="Review"></i></div>
        <div class="movie-create-movie-number">Create Movie</div>
      </div>
      <div class="movie-create-info-section">
        <form action="index.php" method="POST">
          <div class="movie-create-info-movie-title">
            <input type="text" class="form-control" name="movie_title" placeholder="Title" />
          </div>
          <div class="movie-create-info-run-time">
            <input type="text" type="text-area" class="form-control" name="run_time" placeholder="Run Time (Minutes)"></input>
          </div>
          <div class="movie-create-info-rating">
            <select name="rating" class="custom-select" id="inputGroupSelect01">
              <option value="G" selected>G</option>
              <?php
                foreach ($ratings as &$rating) {
              ?>
                <option value="<?php echo $rating; ?>"><?php echo $rating; ?></option>
              <?php
                  }
              ?>
            </select>
          </div>
          <div class="movie-create-info-movie-synopsis">
            <textarea type="text-area" class="form-control" name="movie_synopsis" placeholder="Synopsis"></textarea>
          </div>
          <div class="movie-create-info-director">
            <input type="text" type="text-area" class="form-control" name="director" placeholder="Director Name"></input>
          </div>
          <div class="movie-create-info-production-company">
            <input type="text" type="text-area" class="form-control" name="production_company" placeholder="Production Company Name"></input>
          </div>
          <div class="movie-create-info-supplier">
            <input type="text" type="text-area" class="form-control" name="supplier" placeholder="Supplier Name"></input>
          </div>
          <div class="movie-create-info-start-date">
            <div class="movie-create-info-start-date-badge">Start Date:</div>
            <input type="text" class="form-control date-field" name="start_date_year" value="<?php echo $start_date_year; ?>" placeholder="Year" />
            <input type="text" class="form-control date-field" name="start_date_month" value="<?php echo $start_date_month; ?>" placeholder="Month" />
            <input type="text" class="form-control date-field" name="start_date_date" value="<?php echo $start_date_day; ?>" placeholder="Date" />
          </div>
          <div class="movie-create-info-end-date">
            <div class="movie-create-info-end-date-badge">End Date:</div>
            <input type="text" class="form-control date-field" name="end_date_year" value="<?php echo $end_date_year; ?>" placeholder="Year" />
            <input type="text" class="form-control date-field" name="end_date_month" value="<?php echo $end_date_month; ?>" placeholder="Month" />
            <input type="text" class="form-control date-field" name="end_date_date" value="<?php echo $end_date_day; ?>" placeholder="Date" />
          </div>
          <div class="movie-create-button-container">
            <input name="movie-create-button" type="submit" class="btn btn-success" value="Create Movie" />
          </div>
        </form>
      </div>
    </main>
  </body>
</html>