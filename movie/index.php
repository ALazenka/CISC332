<!DOCTYPE html>
<html>
  <head>
    <title>Movie - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="movie.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="movie-content">
    <?php
      
      include '../components/sql-connect.php';
      $movie_info = [];
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