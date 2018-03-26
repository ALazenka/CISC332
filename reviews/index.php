<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"]) || $_SESSION["account_number"] < 0) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  $reviews = [];
  $get_reviews = "SELECT review.title as review_title, movie.title as movie_title, firstname, lastname, score, review.id as id
                  FROM review
                  JOIN customer 
                    ON review.customer_id=customer.id
                  JOIN movie
                    ON review.movie_id=movie.id
                  ORDER BY customer.firstname, movie_title";
  $result = $conn->query($get_reviews);
  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($reviews, $row);
    }
  }
  $conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Reviews - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="reviews.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="reviews-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="reviews-title-section">
        <h1 class="reviews-title">Reviews
          <a class="btn btn-success add-review-button" href="/CISC332/review-create" role="button">Write Review</a>
        </h1>
        <h4>Hear What Other People Have to Say!</h4>
      </div>
      <?php
        if (isset($_SESSION["review_create_success"]) && $_SESSION["review_create_success"]) {
          $_SESSION["review_create_success"] = false;
      ?>
      <div class="alert alert-success reviews-alert-section" role="alert">
        <strong>Review Created!</strong> You can create a new review at any time.
      </div>
      <?php
        }
      ?>
      <div class="reviews-info-section">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">Author</th>
              <th scope="col">Title</th>
              <th scope="col">Movie Name</th>
              <th scope="col">Score</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($reviews as &$review) {
                
            ?>
            <tr class="table-item" onclick="location.href = '/CISC332/review-details/?review_id=<?php echo $review['id']; ?>';">
              <td><?php echo $review['firstname'] . ' ' . $review['lastname']; ?></td>
              <td><?php echo $review['review_title']; ?></td>
              <td><?php echo $review['movie_title']; ?></td>
              <td><?php echo $review['score']; ?></td>
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