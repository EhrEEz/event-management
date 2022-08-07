<?php require "connection.php"; ?>
<?php
if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
}
?>

<?php
require("fatalFunctions.php");
?>
<?php
//Ehreez U_00A238F2
$link404 = "404.php";
$link500 = "404.php";
$currentUserID = "";
$currentUserFollowingList = [];
$currentUserFollowersList = [];
$currentUserFollowingProfiles = [];
$currentUserFollowersProfiles = [];
if (isset($_SESSION["user_id"])) {
	$currentUserID = $_SESSION["user_id"];
	$currentUserFollowingList = getFollowingList($currentUserID);
	$currentUserFollowersList = getFollowerList($currentUserID);
	$currentUserFollowingProfiles = getFollowingUserProfiles($currentUserID);
	$currentUserFollowersProfiles = getFollowerUserProfiles($currentUserID);
}




$currentUser = getFullUserProfile($currentUserID);

function getFollowingList($userID)
{
	global $connection;
	$userFollowing = "SELECT * FROM `user_favourites` WHERE `ac_user_id` = '$userID'";
	$userFollowingQuery = mysqli_query($connection, $userFollowing);
	$userFollowingList = [];
	if (!$userFollowingQuery) {
		die("Could't fetch user likes" . mysqli_error($connection));
	} else {
		while ($row = mysqli_fetch_assoc($userFollowingQuery)) {
			$userFollowingList[] = $row["friend_user_id"];
		}
	}
	return $userFollowingList;
}
function getFollowerList($userID)
{
	global $connection;
	$userFollowers = "SELECT * FROM `user_favourites` WHERE `friend_user_id` = '$userID'";
	$userFollowersQuery = mysqli_query($connection, $userFollowers);
	$userFollowersList = [];
	if (!$userFollowersQuery) {
		die("Could't fetch user likes" . mysqli_error($connection));
	} else {
		while ($row = mysqli_fetch_assoc($userFollowersQuery)) {
			$userFollowersList[] = $row["ac_user_id"];
		}
	}
	return $userFollowersList;
}
function getFollowingUserProfiles($userID)
{
	$followingUserProfiles = [];
	$userFollowingList = getFollowingList($userID);
	foreach ($userFollowingList as $newUSR) {
		$profile = getFullUserProfile($newUSR);
		$followingUserProfiles[$newUSR] = $profile;
	}
	return $followingUserProfiles;
}
function getFollowerUserProfiles($userID)
{
	$followerUserProfiles = [];
	$userFollowingList = getFollowerList($userID);
	foreach ($userFollowingList as $newUSR) {
		$profile = getFullUserProfile($newUSR);
		$followerUserProfiles[$newUSR] = $profile;
	}
	return $followerUserProfiles;
}

function getFullUserProfile($userID)
{
	global $connection;
	$userProfileQuery = "SELECT * 
	FROM `users` 
	LEFT JOIN `profiles` 
	ON users.user_id= profiles.user_id WHERE users.user_id = '$userID';";
	$userProfile = mysqli_query($connection, $userProfileQuery);
	$currentUserData = [];
	if (!$userProfile) {
		die("User Profile wasn't retrieved successfully: " . mysqli_error($connection));
	} else {
		$currentUserData = mysqli_fetch_assoc($userProfile);
		if (isset($_SESSION["user_id"])) {

			$currentUserData['avatar'] = $currentUserData['avatar'] ? $currentUserData['avatar'] : "default-avatar.png";
			$currentUserData['cover_picture'] = $currentUserData['cover_picture'] ? $currentUserData['cover_picture'] : "defaultCover.jpg";
		}
	}
	return $currentUserData;
}



$role_query = "SELECT * FROM `roles`;";
$roles = mysqli_query($connection, $role_query);
if (!$roles) {
	die("Roles Query wasn't able to successfully execute: " . mysqli_error($connection));
}

function getProfile($userID)
{
	global $connection;
	$getProfileQuery = "SELECT * FROM `profiles` where `user_id` = '$userID'";
	$profile = mysqli_query($connection, $getProfileQuery);
	if (!$profile) {
		die("User Profile could't be retrieved. " . mysqli_error($connection));
		return false;
	} else {
		$relevant_info = [];
		$profile_assoc = mysqli_fetch_assoc($profile);
		$profile_assoc['avatar'] = $profile_assoc['avatar'] ? $profile_assoc['avatar'] : "default-avatar.png";
		$profile_assoc['cover_picture'] = $profile_assoc['cover_picture'] ? $profile_assoc['cover_picture'] : "defaultCover.jpg";
		$relevant_info = $profile_assoc;
		return $relevant_info;
	}
}

function getUserEvents($userID)
{
	global $connection;
	$getEventsQuery = "SELECT `event_id`, `event_name`, `event_start_datetime`, `event_end_datetime` FROM `events` where `event_organizer` = '$userID'";
	$getEvents = mysqli_query($connection, $getEventsQuery);
	if (!$getEvents) {
		die("Couldn't fetch events from databse: " . mysqli_error($connection));
	} else {
		$userEvents = [];
		while ($row = mysqli_fetch_assoc($getEvents)) {
			$userEvents[] = $row;
		}
		return $userEvents;
	}
}

function getUserList()
{
	global $currentUserID, $connection;
	$user_list_query = "SELECT `user_id`, `user_name`, `user_role` FROM `users` WHERE user_id != '$currentUserID';";
	$user_list = mysqli_query($connection, $user_list_query);
	$user_profile_list = [];

	if (!$user_list) {
		die("User List couldn't be generated" . mysqli_error($connection));
	} else {
		while ($user_list_assoc = mysqli_fetch_assoc($user_list)) {
			$userProfile = getProfile($user_list_assoc["user_id"]);
			$user_profile_list["{$user_list_assoc["user_id"]}"] = [
				"profile" => $userProfile,
				"user_name" => $user_list_assoc["user_name"],
				"user_role" => $user_list_assoc["user_role"],
			];
		}
	}
	return $user_profile_list;
}
function getEventList()
{
	global  $connection;
	$event_list_query = "SELECT `event_id`, `event_name`, `event_start_datetime`, `event_end_datetime`, `event_organizer`, `created_date` FROM `events`  WHERE DATE(`event_end_datetime`) > CURRENT_TIME;";
	$event_list = mysqli_query($connection, $event_list_query);
	$events_return_list = [];
	if (!$event_list) {
		die("User List couldn't be generated" . mysqli_error($connection));
	} else {
		while ($event_list_assoc = mysqli_fetch_assoc($event_list)) {
			$events_return_list["{$event_list_assoc["event_id"]}"] = [
				"event_name" => $event_list_assoc["event_name"],
				"event_organizer" => getFullUserProfile($event_list_assoc["event_organizer"]),
				"event_start_datetime" => $event_list_assoc["event_start_datetime"],
				"event_end_datetime" => $event_list_assoc["event_end_datetime"],
				"event_created_datetime" => $event_list_assoc["event_end_datetime"],
			];
		}
	}
	return $events_return_list;
}

?>

<?php

if (isset($_GET['actionLogout']) && $_GET['actionLogout'] == "logout") {
	session_destroy();
	header("Location: login.php");
	exit();
}
