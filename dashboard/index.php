<?php
  session_start();
  if ($_SESSION["logged_in"]) {
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard - OMTS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="dashboard.css" />
    <script src="dashboard.js"></script>
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
  </head>
  <body>
    <nav id="side-menu">
      <ul class="menu-list">
        <a class="toggle close" id="close" href="#">
          <i class="far fa-times-circle" id="menu-close"></i>
        </a>
        <a class="profile-item" href="/CISC332/profile">
          <i class="far fa-user" id="profile-item" title="Profile"></i>
        </a>
        <a class="menu-item" href="/CISC332/showtimes">
          <i class="fas fa-video" id="menu-item" title="Showtimes"></i>
        </a>
        <a class="menu-item" href="/CISC332/movie-list">
          <i class="fas fa-film" id="menu-item" title="Movie List"></i>
        </a>
        <a class="menu-item" href="/CISC332/tickets">
          <i class="fas fa-ticket-alt" id="menu-item" title="Tickets"></i>
        </a>
        <a class="menu-item" href="/CISC332/reviews">
          <i class="far fa-star" id="menu-item" title="Reviews"></i>
        </a>
        <a class="menu-item" href="/CISC332/members">
          <i class="fas fa-users" id="menu-item" title="Members (Admin Only)"></i>
        </a>
      </ul>
    </nav>
    <main class="dashboard-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-bars"></i>
      </a>
    </main>
  </body>
</html>
<?php
  }
?>