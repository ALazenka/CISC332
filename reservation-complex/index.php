<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"])) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  $theater_complexes = [];
  $get_complexes = "SELECT id, name, street, town, province, country, phone_number
            FROM theater_complex";
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
  $conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Reservation - OMTS</title>
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
        <h1 class="theater-complex-title">Reservation - Theater Complex</h1>
        <h4>Select a Theater Complex to View Showtimes</h4>
      </div>
      <?php
        if (isset($_SESSION["reservation_success"]) && $_SESSION["reservation_success"]) {
          $_SESSION["reservation_success"] = false;
      ?>
      <div class="alert alert-success theater-complex-alert-section" role="alert">
        <strong>Tickets Purchased!</strong> You can see all of your movie tickets in the side menu.
      </div>
      <?php
        }
      ?>
      <?php
        if (isset($_SESSION["user_role_redirect"]) && $_SESSION["user_role_redirect"]) {
          $_SESSION["user_role_redirect"] = false;
      ?>
      <div class="alert alert-danger theater-complex-alert-section" role="alert">
        <strong>Page Restricted!</strong> You must be an admin to view that page.
      </div>
      <?php
        }
      ?>
      <div class="theater-complex-info-section">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Theater Complex Name</th>
              <th scope="col">Address</th>
              <th scope="col">Phone Number</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($theater_complexes as &$complex) {
            ?>
            <tr class="table-item" onclick="location.href = '/CISC332/showtimes/?complex_id=<?php echo $complex['id']; ?>';">
              <th scope="row"><?php echo $complex['id']; ?></th>
              <td><?php echo $complex['name']; ?></td>
              <td><?php echo $complex['street'] . ' ' . $complex['town'] . ', ' . $complex['province'] . ', ' . $complex['country']; ?></td>
              <td><?php echo $complex['phone_number']; ?></td>
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