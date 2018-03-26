<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"])) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  $movie_id = 0;
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'movie_id=') !== false) {
      $movie_id = str_replace("movie_id=", "", $item);
    } 
  };
  
  if (isset($_POST["review-create-button"])) {
    $movie_id = $_POST["movie_id"];
    $review_title = $_POST["review_title"];
    $review_content = $_POST["review_content"];
    $review_score = $_POST["review_score"];
    $customer_id = $_SESSION["user_id"];
    $create_review = "INSERT INTO Review (`title`, `content`, `score`, `movie_id`, `customer_id`, `id`)
                      VALUES ('$review_title', '$review_content', '$review_score', '$movie_id', '$customer_id', '')";
    $conn->query($create_review);
    $get_reviews = "SELECT * FROM review ORDER BY id DESC";
    $review_list = $conn->query($get_reviews);
    $loop_count = 0;
    $new_review = "";
    while($loop_count != 1 && $row = $review_list->fetch_assoc()) {
      $new_review = $row;
      $loop_count++;
    }
    echo $new_review["id"];
    $create_customer_review = "INSERT INTO customer_review
                      VALUES ('', '" . $_SESSION['user_id'] . "', '" . $new_review["id"] . "')";
    $create_movie_review = "INSERT INTO movie_review
                      VALUES ('', '" . $new_review["id"] . "', '" . $movie_id . "')";
    $conn->query($create_customer_review);
    $conn->query($create_movie_review);
    $_SESSION["review_create_success"] = true;
    header("Location: ../reviews");
  }

  $movie_list = [];
  $get_movies = "SELECT * FROM movie";
  $result_movies = $conn->query($get_movies);
  $conn->close();
  if ($result_movies->num_rows > 0) {
    while($row = $result_movies->fetch_assoc()) {
      array_push($movie_list, $row);
    }
  }
?>
<html>
  <head>
    <title>Review Create - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="review-create.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="review-create-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="review-create-picture-section">
        <div class="review-create-default-image"><i class="far fa-star" id="review-create-image" title="Review"></i></div>
        <div class="review-create-review-number">Create Review</div>
      </div>
      <div class="review-create-info-section">
        <form action="index.php" method="POST">
          <div class="review-create-info-movie-select">
            <div class="review-create-info-movie-select-badge">Select Movie:</div>
            <select name="movie_id" class="custom-select" id="inputGroupSelect01">
              <option selected>None</option>
              <?php
                foreach ($movie_list as &$movie) {
                  if ($movie_id == $movie["id"]) {
              ?>
                <option value="<?php echo $movie["id"]; ?>" selected><?php echo $movie["title"]; ?></option>
              <?php
                  } else {
              ?>
                <option value="<?php echo $movie["id"]; ?>"><?php echo $movie["title"]; ?></option>
              <?php
                  }
                }
              ?>
            </select>
          </div>
          <div class="review-create-info-score-select">
            <div class="review-create-info-score-select-badge">Score:</div>
            <select name="review_score" class="custom-select" id="inputGroupSelect01">
              <?php
                foreach (range(0,5) as $score) {
              ?>
                <option value="<?php echo $score; ?>"><?php echo $score; ?></option>
              <?php
                }
              ?>
            </select>
          </div>
          <div class="review-create-info-review-title">
            <input type="text" class="form-control" name="review_title" placeholder="Review Title" />
          </div>
          <div class="review-create-info-review-content">
            <textarea type="text-area" class="form-control" name="review_content" placeholder="Review Content"></textarea>
          </div>
          <div class="review-create-button-container">
            <input name="review-create-button" type="submit" class="btn btn-success" value="Create Review" />
          </div>
        </form>
      </div>
    </main>
  </body>
</html>