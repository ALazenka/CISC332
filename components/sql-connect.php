<?php
  // server variables
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "cisc332";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
?>