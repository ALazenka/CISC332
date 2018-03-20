<?php
  include '../components/sql-connect.php';
  session_start();

  $reviews = [];
  $get_reviews = "SELECT review.title as review_title, movie.title as movie_title, firstname, lastname, score, review.id as id
                  FROM review 
                  JOIN customer_review 
                    ON review.id=customer_review.review_id 
                  JOIN customer 
                    ON customer_review.customer_id=customer.id
                  JOIN movie_review
                    ON review.id=movie_review.review_id
                  JOIN movie
                    ON movie_review.movie_id=movie.id";
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
    <title>Tickets - OMTS</title>
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
        <h1 class="reviews-title">Reviews</h1>
      </div>
      <div class="reviews-info-section">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
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
            <tr class="table-item" onclick="location.href = '/CISC332/review-details/?id=<?php echo $review['id']; ?>';">
              <th scope="row"><?php echo $review['id']; ?></th>
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