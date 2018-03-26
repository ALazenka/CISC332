<?php
  session_start();
  if (!isset($_SESSION["account_number"])) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }
  include '../components/sql-connect.php';

  $movie_info = [];
  $movie_id = 0;
  $movie_edit = false;
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'movie_id=') !== false) {
      $movie_id = str_replace("movie_id=", "", $item);
    } else if (strpos($item, 'edit_id=') !== false) {
      $movie_id = str_replace("edit_id=", "", $item);
      $_SESSION["edit_movie_id"] = $movie_id;
      $movie_edit = true;
    } 
  };

  if (isset($_POST["movie-edit-button"])) {
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
    $edit_movie = "UPDATE movie SET title = '$movie_title', run_time = '$run_time', rating = '$rating', synopsis = '$movie_synopsis', director = '$director', production_company = '$production_company', supplier_name =  '$supplier', start_date =  '$start_date', end_date = '$end_date' WHERE id='" . $_SESSION["edit_movie_id"] . "'";
    $conn->query($edit_movie);
    header("Location: ../movie/?movie_id=" . $_SESSION["edit_movie_id"]);
  }

  $get_movie_info = "SELECT id, title, run_time, rating, synopsis, director, production_company, supplier_name, start_date, end_date FROM movie WHERE id='" . $movie_id . "'";
  $result = $conn->query($get_movie_info);

  $conn->close();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $movie_info = $row;
    }
  }

  $start_date_year = ""; 
  $start_date_month = ""; 
  $start_date_day = "";
  $end_date_year = "";
  $end_date_month = "";
  $end_date_day = "";
  if (strlen($movie_info["start_date"]) == 7) {
    $start_date_year = substr($movie_info["start_date"], 3, 4);
    $start_date_month = substr($movie_info["start_date"], 1, 2);
    $start_date_day = "0" . substr($movie_info["start_date"], 0, 1);
  } else {
    $start_date_year = substr($movie_info["start_date"], 4, 4);
    $start_date_month = substr($movie_info["start_date"], 2, 2);
    $start_date_day = substr($movie_info["start_date"], 0, 2);
  }
  if (strlen($movie_info["end_date"]) == 7) {
    $end_date_year = substr($movie_info["end_date"], 3, 4);
    $end_date_month = substr($movie_info["end_date"], 1, 2);
    $end_date_day = "0" . substr($movie_info["end_date"], 0, 1);
  } else {
    $end_date_year = substr($movie_info["end_date"], 4, 4);
    $end_date_month = substr($movie_info["end_date"], 2, 2);
    $end_date_day = substr($movie_info["end_date"], 0, 2);
  }
  $start_date = $start_date_day . " - " . $start_date_month . " - " . $start_date_year;
  $end_date = $end_date_day . " - " . $end_date_month . " - " . $end_date_year;
?>
<html>
  <head>
    <title>Movie - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="movie.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="movie-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="movie-picture-section">
        <div class="movie-default-image"><i class="fas fa-film" id="movie-image" title="Movie"></i></div>
        <div class="movie-account-number"><?php echo $movie_info["id"]; ?></div>
      </div>
      <div class="movie-info-section">
        <?php if (!$movie_edit) { ?>
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
          <div class="movie-info-start-date-field"><?php echo $start_date; ?></div>
        </div>
        <div class="movie-info-end-date">
          <div class="movie-info-end-date-badge">End Date:</div>
          <div class="movie-info-end-date-field"><?php echo $end_date; ?></div>
        </div>
      </div>
      <?php } else { ?>
        <form action="index.php" method="POST">
          <div class="movie-edit-info-movie-title">
            <input type="text" class="form-control" name="movie_title" value="<?php echo $movie_info["title"]; ?>" placeholder="Title" />
          </div>
          <div class="movie-edit-info-run-time">
            <input type="text" type="text-area" class="form-control" name="run_time" value="<?php echo $movie_info["run_time"]; ?>" placeholder="Run Time (Minutes)" />
          </div>
          <div class="movie-edit-info-rating">
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
          <div class="movie-edit-info-movie-synopsis">
            <textarea type="text-area" class="form-control" name="movie_synopsis" placeholder="Content"><?php echo $movie_info["synopsis"]; ?></textarea>
          </div>
          <div class="movie-edit-info-director">
            <input type="text" type="text-area" class="form-control" name="director" value="<?php echo $movie_info["director"]; ?>" placeholder="Director Name" />
          </div>
          <div class="movie-edit-info-production-company">
            <input type="text" type="text-area" class="form-control" name="production_company" value="<?php echo $movie_info["production_company"]; ?>" placeholder="Production Company Name" />
          </div>
          <div class="movie-edit-info-supplier">
            <input type="text" type="text-area" class="form-control" name="supplier" value="<?php echo $movie_info["supplier_name"]; ?>" placeholder="Supplier Name" />
          </div>
          <div class="movie-edit-info-start-date">
            <div class="movie-edit-info-start-date-badge">Start Date:</div>
            <input type="text" class="form-control date-field" name="start_date_year" value="<?php echo $start_date_year; ?>" placeholder="Year" />
            <input type="text" class="form-control date-field" name="start_date_month" value="<?php echo $start_date_month; ?>" placeholder="Month" />
            <input type="text" class="form-control date-field" name="start_date_date" value="<?php echo $start_date_day; ?>" placeholder="Date" />
          </div>
          <div class="movie-edit-info-end-date">
            <div class="movie-edit-info-end-date-badge">End Date:</div>
            <input type="text" class="form-control date-field" name="end_date_year" value="<?php echo $end_date_year; ?>" placeholder="Year" />
            <input type="text" class="form-control date-field" name="end_date_month" value="<?php echo $end_date_month; ?>" placeholder="Month" />
            <input type="text" class="form-control date-field" name="end_date_date" value="<?php echo $end_date_day; ?>" placeholder="Date" />
          </div>
          <div class="movie-edit-button-container">
            <input name="movie-edit-button" type="submit" class="btn btn-success" value="Edit Movie" />
          </div>
        <?php }
          if (!$movie_edit) {
        ?>
        <div class="movie-edit-section">
          <div>
            <button id="movie-create-button" type="button" class="btn btn-success" onclick="location.href = '/CISC332/review-create/?movie_id=<?php echo $movie_info['id']; ?>';">Create Review</button>
          </div>
          <?php
            if ($_SESSION["user_role"] == 1) {
          ?>
            <div>
              <button id="movie-edit-button" type="button" class="btn btn-primary" onclick="location.href = '/CISC332/movie/?edit_id=<?php echo $movie_id; ?>'">Edit</button>
            </div>
          <?php
            }
          }
          ?>
        </div>
      </form>
    </main>
  </body>
</html>