<nav id="side-menu">
  <ul class="menu-list">
    <a class="toggle close" id="close" href="#">
      <i class="far fa-times-circle" id="menu-close"></i>
    </a>
    <a class="top-menu-item" href="/CISC332/reservation-complex" data-toggle="tooltip" data-placement="right" title="Reservation">
      <i class="fas fa-video" id="menu-item"></i>
    </a>
    <a class="menu-item" href="/CISC332/tickets" data-toggle="tooltip" data-placement="right" title="Tickets">
      <i class="fas fa-ticket-alt" id="menu-item"></i>
    </a>
    <a class="menu-item" href="/CISC332/movie-list" data-toggle="tooltip" data-placement="right" title="Movie List">
      <i class="fas fa-film" id="menu-item"></i>
    </a>
    <a class="menu-item" href="/CISC332/reviews" data-toggle="tooltip" data-placement="right" title="Reviews">
      <i class="far fa-star" id="menu-item"></i>
    </a>
    <?php
      if ($_SESSION["user_role"] == 1) {
    ?>
      <a class="menu-item" href="/CISC332/theater-complex" data-toggle="tooltip" data-placement="right" title="Manage Theaters">
        <i class="fas fa-tv" id="menu-item"></i>
      </a>
      <a class="menu-item" href="/CISC332/showings" data-toggle="tooltip" data-placement="right" title="Manage Showings">
        <i class="fas fa-list" id="menu-item"></i>
      </a>
      <a class="menu-item" href="/CISC332/admin" data-toggle="tooltip" data-placement="right" title="Admin Stats">
        <i class="fas fa-chart-line" id="menu-item"></i>
      </a>
      <a class="menu-item" href="/CISC332/members" data-toggle="tooltip" data-placement="right" title="Members">
        <i class="fas fa-users" id="menu-item"></i>
      </a>
    <?php
      }
    ?>
    <a class="menu-item" href="/CISC332/profile" data-toggle="tooltip" data-placement="right" title="Profile">
      <i class="far fa-user" id="menu-item"></i>
    </a>
    <a class="menu-item" href="/CISC332/logout" data-toggle="tooltip" data-placement="right" title="Logout">
      <i class="fas fa-sign-out-alt" id="menu-item"></i>
    </a>
  </ul>
</nav>
<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });
</script>
