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

  $theater_complexes = [];
  $get_complexes = "SELECT theater_complex.id, name, street, town, province, country, phone_number
                    FROM theater_complex
                    ORDER BY name";
  $result = $conn->query($get_complexes);

  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($theater_complexes, $row);
    }
  }

  $theaters = [];
  $get_theaters = "SELECT theater.id, theater.theater_number, theater.screen_size, theater_complex.name as complex_name
                  FROM theater
                  JOIN theater_complex
                    ON theater.theater_complex_id=theater_complex.id
                  ORDER BY complex_name, theater_number";
  $result = $conn->query($get_theaters);

  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($theaters, $row);
    }
  }
  $conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Theater Complexes - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="theater-complex.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="theater-complex-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
      <div class="theater-complex-title-section">
        <h1 class="theater-complex-title">Theater Complexes
          <a class="btn btn-success add-complex-button" href="/CISC332/theater-complex-create" role="button">Add Complex</a>
          <a class="btn btn-success add-theater-button" href="/CISC332/theater-create" role="button">Add Theater</a>
        </h1>
        <h4>View and Manage Theater Complexes</h4>
      </div>
      <?php
        if (isset($_SESSION["complex_create_success"]) && $_SESSION["complex_create_success"]) {
          $_SESSION["complex_create_success"] = false;
      ?>
      <div class="alert alert-success theater-complex-alert-section" role="alert">
        <strong>Theater Complex Created!</strong>
      </div>
      <?php
        }
      ?>
      <?php
        if (isset($_SESSION["complex_edit_success"]) && $_SESSION["complex_edit_success"]) {
          $_SESSION["complex_edit_success"] = false;
      ?>
      <div class="alert alert-success theater-complex-alert-section" role="alert">
        <strong>Theater Complex Updated!</strong>
      </div>
      <?php
        }
        if (isset($_SESSION["theater_create_success"]) && $_SESSION["theater_create_success"]) {
          $_SESSION["theater_create_success"] = false;
      ?>
      <div class="alert alert-success theater-complex-alert-section" role="alert">
        <strong>Theater Created!</strong>
      </div>
      <?php
        }
      ?>
      <?php
        if (isset($_SESSION["theater_edit_success"]) && $_SESSION["theater_edit_success"]) {
          $_SESSION["theater_edit_success"] = false;
      ?>
      <div class="alert alert-success theater-complex-alert-section" role="alert">
        <strong>Theater Updated!</strong>
      </div>
      <?php
        }
      ?>
      <div class="theater-complex-info-section">
        <h4>Theater Complexes</h4>
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">Theater Complex Name</th>
              <th scope="col">Address</th>
              <th scope="col">Phone Number</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($theater_complexes as &$complex) {
            ?>
            <tr class="table-item" onclick="location.href = '/CISC332/theater-complex-details/?complex_id=<?php echo $complex['id']; ?>';">
              <td><?php echo $complex['name']; ?></td>
              <td><?php echo $complex['street'] . ' ' . $complex['town'] . ', ' . $complex['province'] . ', ' . $complex['country']; ?></td>
              <td><?php echo $complex['phone_number']; ?></td>
            </tr>
            <?php
              }
            ?>
          </tbody>
        </table>
        <h4 class="lower-table">Theaters</h4>
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">Theater Complex Name</th>
              <th scope="col">Theater Number</th>
              <th scope="col">Screen</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($theaters as &$theater) {
            ?>
            <tr class="table-item" onclick="location.href = '/CISC332/theater-details/?theater_id=<?php echo $theater['id']; ?>';">
              <td><?php echo $theater['complex_name']; ?></td>
              <td><?php echo $theater['theater_number']; ?></td>
              <td><?php echo $theater['screen_size']; ?></td>
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