<?php

try{

  $host = "localhost";
  $username = "root";
  $password = "";
  $database = "smartstock_inventory";

  $conn = new mysqli($host, $username, $password, $database);
  
  if ($conn->connect_error) {
    die("Database connection unsuccessful". $conn->connect_error);
  }

} catch(Exception $e){
    echo "Error: ".$e;
}

?>