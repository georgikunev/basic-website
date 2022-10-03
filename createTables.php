<?php
$servername = "localhost";
$username = "root";
$password = "adminroot";
$dbname = "mydb";


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// sql to create table

    $sql ="CREATE TABLE IF NOT EXISTS`accounts` (
  `acc_id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `lastEntered` datetime DEFAULT NULL,
  PRIMARY KEY (`acc_id`)
  );";

	/*$sql = "CREATE TABLE IF NOT EXISTS `members` (
  `member_id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `phone` varchar(30) NOT NULL,
  PRIMARY KEY (`member_id`)
);";*/

if ($conn->query($sql) === TRUE) {
  echo "Tables created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}

$conn->close();

?>
