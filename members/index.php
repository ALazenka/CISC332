<?php
  include '../components/sql-connect.php';
  session_start();
  if (!isset($_SESSION["account_number"]) || $_SESSION["account_number"] < 0) {
    $_SESSION["login_redirect"] = true;
    header("Location: ../login");
  }

  if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] != 1) {
    $_SESSION["user_role_redirect"] = true;
    header("Location: ../reservation-complex");
  }
  
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  $remove_id = 0;
  foreach (parse_url($url) as &$item) {
    if (strpos($item, 'remove_id=') !== false) {
      $remove_id = str_replace("remove_id=", "", $item);
    } 
  };

  if ($remove_id > 0 && $_SESSION["user_role"] == 1) {
    $remove_user_query = "DELETE FROM customer WHERE id=$remove_id";
    $conn->query($remove_user_query);
    $_SESSION["remove_user_success"] = true;
  }

  $members = [];
  $get_members = "SELECT *
                  FROM customer";
  $result = $conn->query($get_members);
  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($members, $row);
    }
  }
  $conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Members - OMTS</title>
    <link rel="stylesheet" type="text/css" media="screen" href="members.css" />
    <?php include '../components/head-contents.php'; ?>
  </head>
  <body>
    <?php include '../components/side-menu.php'; ?>
    <main class="members-content">
      <a class="toggle open" id="open" href="#side-menu">
        <i class="fas fa-users"></i>
      </a>
      <div class="members-title-section">
        <h1 class="members-title">Members</h1>
        <h3>View and Remove Members</h3>
      </div>
      <?php
        if (isset($_SESSION["remove_user_success"]) && $_SESSION["remove_user_success"]) {
          $_SESSION["remove_user_success"] = false;
      ?>
        <div class="alert alert-success members-alert-section" role="alert">
          <strong>Member Removed!</strong> You just removed member with account number <?php echo $remove_id; ?>.
        </div>
      <?php
        }
      ?>
      <div class="members-info-section">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">Account Number</th>
              <th scope="col">First Name</th>
              <th scope="col">Last Name</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col">Street</th>
              <th scope="col">Town</th>
              <th scope="col">Postal Code</th>
              <th scope="col">State/Province</th>
              <th scope="col">Country</th>
              <th scope="col">Phone Number</th>
              <th scope="col">Remove</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($members as &$member) {
                
            ?>
            <tr class="table-item" onclick="location.href = '/CISC332/profile/?account_number=<?php echo $member['account_number']; ?>';">
              <th scope="row"><?php echo $member['account_number']; ?></th>
              <td><?php echo $member['firstname']; ?></td>
              <td><?php echo $member['lastname']; ?></td>
              <td><?php echo $member['email_address']; ?></td>
              <td><?php echo ($member['role'] == 0 ? 'Customer' : 'Admin'); ?></td>
              <td><?php echo $member['street']; ?></td>
              <td><?php echo $member['town']; ?></td>
              <td><?php echo $member['postalcode']; ?></td>
              <td><?php echo $member['province']; ?></td>
              <td><?php echo $member['country']; ?></td>
              <td><?php echo $member['phone_number']; ?></td>
              <td class="remove-cell">
                <a class="remove-user-link" href="/CISC332/members/?remove_id=<?php echo $member["id"]; ?>">
                  <i class="far fa-trash-alt remove-user"></i>
                </a>
              </td>
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