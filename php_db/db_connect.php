<?php 
error_reporting(0);
$localhost = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "tech";

// create connection
$connect = new mysqli($localhost, $username, $password, $dbname);

// check connection
if($connect->connect_error) {
	die("connection failed : " . $connect->connect_error);
} else {
	// echo "Successfully Connected";
	$connect->query("SET CHARSET utf8");
    $connect->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
}

?>
