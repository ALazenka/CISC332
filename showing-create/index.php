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

  if (isset($_POST["showing-create-button"])) {
    $theater_complex_id = $_POST["theater_complex_id"];
    $theater_id = $_POST["theater_id"];
    $movie_id = $_POST["movie_id"];
    $start_time = $_POST["start_time"];
    $create_showing = "INSERT INTO Showing (`theater_complex_id`, `theater_id`, `movie_id`, `start_time`, `id`)
                      VALUES ('$theater_complex_id', '$theater_id', '$movie_id', '$start_time', '')";
    $conn->query($create_showing);
    header("Location: ../showings");
  }

  $movie_list = [];
  $theater_complex_list = [];
  $theater_list = [];
  $complex_id = 0;
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'complex_id=') !== false) {
      $complex_id = str_replace("complex_id=", "", $item);
    }
  };

  $get_complexes = "SELECT * FROM theater_complex ORDER BY name";
  $result = $conn->query($get_complexes);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($theater_complex_list, $row);
    }
  }

  if ($complex_id != 0) {

    $get_movies = "SELECT * FROM movie ORDER BY title";
    $result = $conn->query($get_movies);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        array_push($movie_list, $row);
      }
    }

    $get_theaters = "SELECT * FROM theater WHERE theater_complex_id = $complex_id ORDER BY theater_number";
    $result = $conn->query($get_theaters);
    $conn->close();
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        array_push($theater_list, $row);
      }
    }
  }
?>
<html>
  <head>
    <title>Showing Create - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="showing-create.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="showing-create-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="showing-create-picture-section">
        <div class="showing-create-default-image"><i class="far fa-star" id="showing-create-image" title="Review"></i></div>
        <div class="showing-create-showing-number">Create Showing</div>
      </div>
      <div class="showing-create-info-section">
        <form action="index.php" method="POST">
          <div class="showing-create-info-complex">
            <div class="showing-create-info-complex-badge">Theater Complex:</div>
            <select name="theater_complex_id" class="custom-select" onchange="changeComplex(this.value)">
              <option selected>None</option>
              <?php
                foreach ($theater_complex_list as &$complex) {
                  if ($complex["id"] == $complex_id) {
              ?>
              <option value="<?php echo $complex["id"]; ?>" selected><?php echo $complex["name"]; ?></option>
              <?php
                  } else {
              ?>
              <option value="<?php echo $complex["id"]; ?>"><?php echo $complex["name"]; ?></option>
              <?php
                  }
                }
              ?>
            </select>
          </div>
          <?php if ($complex_id != 0) { ?>
          <div class="showing-create-info-theater-number">
            <div class="showing-create-info-theater-number-badge">Theater Number:</div>
            <select name="theater_id" class="custom-select">
              <?php
                  foreach ($theater_list as &$theater) {
              ?>
              <option value="<?php echo $theater["id"]; ?>"><?php echo $theater["theater_number"]; ?></option>
              <?php
                  }
              ?>
            </select>
          </div>
          <div class="showing-create-info-movie">
            <div class="showing-create-info-movie-badge">Movie:</div>
            <select name="movie_id" class="custom-select">
              <?php
                  foreach ($movie_list as &$movie) {
              ?>
              <option value="<?php echo $movie["id"]; ?>"><?php echo $movie["title"]; ?></option>
              <?php
                  }
              ?>
            </select>
          </div>
          <div class="showing-create-info-start-time">
            <input type="text" class="form-control" name="start_time" placeholder="Start Time" />
          </div>
          <div class="showing-create-button-container">
            <input name="showing-create-button" type="submit" class="btn btn-success" value="Create Showing" />
          </div>
          <?php } ?>
        </form>
      </div>
    </main>
  </body>
</html>
<script>
function changeComplex(val) {
  if (val == "None") {
    location.href = "/CISC332/showing-create";
  } else {
    location.href = "/CISC332/showing-create/?complex_id=" + val;
  }
}
</script>