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
  
  if (isset($_POST["theater-complex-create-button"])) {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $street = $_POST["street"];
    $town = $_POST["town"];
    $province = $_POST["province"];
    $country = $_POST["country"];
    $postalcode = $_POST["postalcode"];
    $create_complex = "INSERT INTO theater_complex (`name`, `phone_number`, `street`, `town`, `province`, `country`, `postalcode`)
                      VALUES ('$name', '$phone', '$street', '$town', '$province', '$country', '$postalcode')";
    $conn->query($create_complex);
    $_SESSION["complex_create_success"] = true;
    header("Location: ../theater-complex");
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
    <title>Complex Create - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="theater-complex-create.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="theater-complex-create-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="theater-complex-create-picture-section">
        <div class="theater-complex-create-default-image"><i class="far fa-star" id="theater-complex-create-image" title="Review"></i></div>
        <div class="theater-complex-create-theater-complex-number">Create Complex</div>
      </div>
      <div class="theater-complex-create-info-section">
        <form action="index.php" method="POST">
          <div class="theater-complex-create-info-name">
            <input type="text" class="form-control" name="name" placeholder="Complex Name" />
          </div>
          <div class="theater-complex-create-info-phone">
            <input type="text" class="form-control" name="phone" placeholder="Phone Number" />
          </div>
          <div class="theater-complex-info-address">Address:</div>
          <div class="flex-display margin-top">
            <input class="theater-complex-info-name-field form-control" name="street" placeholder="Street" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-info-name-field form-control" name="town" placeholder="Town/City" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-info-name-field form-control" name="province" placeholder="State/Province" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-info-name-field form-control" name="country" placeholder="Country" type="text" />
          </div>
          <div class="flex-display margin-top">
            <input class="theater-complex-info-name-field form-control" name="postalcode" placeholder="Postal Code" type="text" />
          </div>
          <div class="theater-complex-create-button-container">
            <input name="theater-complex-create-button" type="submit" class="btn btn-success" value="Create Complex" />
          </div>
        </form>
      </div>
    </main>
  </body>
</html>