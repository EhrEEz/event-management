<?php require "data.php"; ?>

<?php
if ($currentUserID === $_POST['user_id']) {
	$check = "SELECT * FROM `user_favourites` WHERE `ac_user_id` = '$_POST[user_id]' AND `friend_user_id` = '$_POST[friend_user_id]'";
	$checkQuery = mysqli_query($connection, $check);
	$liked = false;
	if (!$checkQuery) {
		die("Couldn't Check Connect with friend: " . mysqli_error($connection));
	} else {
		if (mysqli_num_rows($checkQuery) != 0) {
			$liked = true;
		} else {
			$liked = false;
		}
	}
	if (!$liked) {
		$connect = "INSERT INTO `user_favourites`(`ac_user_id`, `friend_user_id`, `private`) VALUES ('$_POST[user_id]','$_POST[friend_user_id]',0)";
		$connectQuery = mysqli_query($connection, $connect);
		if (!$connectQuery) {
			die("Couldn't Connect with friend: " . mysqli_error($connection));
		} else {
			echo json_encode(["success" => 1, "liked" => 1]);
		}
	} else {
		$deleteConnect = "DELETE FROM `user_favourites` WHERE `ac_user_id` = '$_POST[user_id]' AND `friend_user_id` = '$_POST[friend_user_id]'";
		$deleteConnectQuery = mysqli_query($connection, $deleteConnect);
		if (!$deleteConnectQuery) {
			die("Couldn't Connect with friend: " . mysqli_error($connection));
		} else {
			echo json_encode(["success" => 1, "liked" => 0]);
		}
	}
}
?>