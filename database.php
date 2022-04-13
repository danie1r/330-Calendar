<?php
// Content of database.php, taken from course wiki

$mysqli = new mysqli('localhost', 'testUser', 'testPassword', 'module5');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>