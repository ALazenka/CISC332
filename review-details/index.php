<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"])) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  $review_info = "";
  $review_id = 0;
  $review_edit = false;
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'review_id=') !== false) {
      $review_id = str_replace("review_id=", "", $item);
    } else if (strpos($item, 'edit_id=') !== false) {
      $review_id = str_replace("edit_id=", "", $item);
      $_SESSION["edit_review_id"] = $review_id;
      $review_edit = true;
    }
  };

  $get_review_info = "SELECT review.id, review.title as review_title, content, score, movie.title as movie_title, movie.id as movie_id, customer.firstname, customer.lastname, customer.account_number
                      FROM review
                      JOIN movie
                        ON review.movie_id=movie.id
                      JOIN customer_review 
                        ON review.id=customer_review.review_id 
                      JOIN customer 
                        ON customer_review.customer_id=customer.id
                      WHERE review.id='" . $review_id . "'";
  $result = $conn->query($get_review_info);
  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $review_info = $row;
    }
  }

  if (isset($_POST["review-edit-button"])) {
    $movie_id = $_POST["movie_id"];
    $review_title = $_POST["review_title"];
    $review_content = $_POST["review_content"];
    $review_score = $_POST["review_score"];
    $review_id = $_SESSION["edit_review_id"];
    echo $review_score;
    $update_review = "UPDATE review SET title = '$review_title', content = '$review_content', score = '$review_score', movie_id = '$movie_id' WHERE review.id = '$review_id'"; 
    $conn->query($update_review);
    header("Location: ../review-details/?review_id=$review_id");
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
    <title>Review Details - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="review-details.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="review-details-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="review-details-picture-section">
        <div class="review-details-default-image"><i class="far fa-star" id="review-details-image" title="Review"></i></div>
        <div class="review-details-account-number"><?php echo $review_info["id"]; ?></div>
      </div>
      <div class="review-details-info-section">
        <?php if (!$review_edit) { ?>
        <div class="review-details-info-movie-title">
          <div class="review-details-info-movie-title-badge">Movie Title:</div>
          <div class="review-details-info-movie-title-field"><?php echo $review_info["movie_title"]; ?></div>
        </div>
        <div class="review-details-info-author">
          <div class="review-details-info-author-badge">Author:</div>
          <div class="review-details-info-author-field"><?php echo $review_info["firstname"] . ' ' . $review_info["lastname"]; ?></div>
        </div>
        <div class="review-details-info-review-title">
          <div class="review-details-info-review-title-badge">Review Title:</div>
          <div class="review-details-info-review-title-field"><?php echo $review_info["review_title"]; ?></div>
        </div>
        <div class="review-details-info-content">
          <div class="review-details-info-content-badge">Content:</div>
          <div class="review-details-info-content-field"><?php echo $review_info["content"]; ?></div>
        </div>
        <div class="review-details-info-score">
          <div class="review-details-info-score-badge">Score:</div>
          <div class="review-details-info-score-field"><?php echo $review_info["score"]; ?></div>
        </div>
      </div>
      <div class="review-details-edit-section">
        <?php
          if ($_SESSION["account_number"] == $review_info["account_number"]) {
        ?>
          <a id="review-details-edit-button" class="btn btn-primary" href="/CISC332/review-details/?edit_id=<?php echo $review_info["id"] ?>" role="button">Edit</a>
        <?php
          }
        ?>
      </div>
      <?php } else { ?>
      <form action="index.php" method="POST">
        <div class="review-edit-info-movie-select">
          <div class="review-edit-info-movie-select-badge">Select Movie:</div>
          <select name="movie_id" class="custom-select" id="inputGroupSelect01">
            <?php
              foreach ($movie_list as &$movie) {
                if ($review_info["movie_id"] == $movie["id"]) {
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
        <div class="review-edit-info-review-title">
          <input type="text" class="form-control" name="review_title" value="<?php echo $review_info["review_title"]; ?>" placeholder="Review Title" />
        </div>
        <div class="review-edit-info-review-content">
          <textarea type="text-area" class="form-control" name="review_content" placeholder="Review Content"><?php echo $review_info["content"]; ?></textarea>
        </div>
        <div class="review-edit-info-score-select">
          <div class="review-edit-info-score-select-badge">Score:</div>
          <select name="review_score" class="custom-select" id="inputGroupSelect01">
            <?php
              foreach (range(0,5) as $score) {
                if ($review_info["score"] == $score) {
            ?>
              <option value="<?php echo $score; ?>" selected><?php echo $score; ?></option>
              <?php
                } else {
              ?>
              <option value="<?php echo $score; ?>"><?php echo $score; ?></option>
            <?php
                }
              }
            ?>
          </select>
        </div>
        <div class="review-edit-button-container">
          <input name="review-edit-button" type="submit" class="btn btn-success" value="Edit Review" />
        </div>
      </form>
      <?php } ?>
    </main>
  </body>
</html>