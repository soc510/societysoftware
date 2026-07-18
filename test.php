<?php
$servername = "173.201.180.16";
$username = "vedhtkjm_easylog";
$password = "easyDatabase123";
try {
  $conn = new PDO("mysql:host=$servername;port=22 ;dbname=vedhtkjm_easylogics", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>