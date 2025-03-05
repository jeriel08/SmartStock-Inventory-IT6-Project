<?php

try {

  $host = "localhost";
  $username = "root";
  $password = "";
  $database = "it6_smartstock_backup";

  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Database connection unsuccessful" . $conn->connect_error);
  }
} catch (Exception $e) {
  echo "Error: " . $e;
}
