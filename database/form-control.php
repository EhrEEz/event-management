<?php
function sanitizeData($data)
{
	global $connection;
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	$data = htmlentities($data);
	$data = str_replace("'", "&0145;", $data);
	$data = str_replace('"', "&0147;", $data);
	return $data;
}
function cln($field, $len)
{
	if (strlen($field) >= $len) {
		return true;
	} else {
		return false;
	}
}
function isRealDate($date)
{
	if (false === strtotime($date)) {
		return false;
	}
	list($year, $month, $day) = explode("-", $date);
	return checkdate($month, $day, $year);
}
function seu($fieldName, $fieldValue, $exclude = "")
{
	global $connection;
	$exclusion = $exclude ? "AND `user_id` != '$exclude'" : "";
	$check_user = "SELECT * FROM `users` where `$fieldName` = '$fieldValue' $exclusion";
	$query = mysqli_query($connection, $check_user);
	if (!$query) {
		die(" Couldn't query..." . mysqli_error($connection));
	} else {
		if (mysqli_num_rows($query) !== 0) {
			return false;
		} else {
			return true;
		}
	}
}
