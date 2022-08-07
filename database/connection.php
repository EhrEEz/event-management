<?php
ob_start();
session_start();
?>
<?php
$db_name = "user-management";
$db_url = "localhost";
$db_username = "root";
$db_password = "";

$connection = mysqli_connect($db_url, $db_username, $db_password, $db_name);

if (!$connection) {
	die("Error while connection " . mysqli_connect_error());
}
?>