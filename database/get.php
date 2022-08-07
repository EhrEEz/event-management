<?php require "data.php"; ?>
<?php

if ($_GET["get"] == "userInvites") {
	$getInvitesQuery = "SELECT * FROM `invitees` WHERE `user_id` = '$currentUserID' ";
	$getInvites = mysqli_query($connection, $getInvitesQuery);
	if (!$getInvites) {
		die("Couldn't fetch invites from databse: " . mysqli_error($connection));
	} else {
		$userInvites = [];
		while ($row = mysqli_fetch_assoc($getInvites)) {
			$userInvites[] = $row;
		}
		$res = ["status" => 200, "data" => $userInvites];
		echo json_encode($res);
	}
}
if ($_GET["get"] == "userEvents") {
	$getEventsQuery = "SELECT `event_id`, `event_name`, `event_start_datetime` FROM `events` where `event_organizer` = '$currentUserID'";
	$getEvents = mysqli_query($connection, $getEventsQuery);
	if (!$getEvents) {
		die("Couldn't fetch events from databse: " . mysqli_error($connection));
	} else {
		$userEvents = [];
		while ($row = mysqli_fetch_assoc($getEvents)) {
			$userEvents[] = $row;
		}
		$res = ["status" => 200, "data" => $userEvents];
		echo json_encode($res);
	}
}
if ($_GET["get"] == "eventDetails") {
	if (!$_GET["id"]) {
		echo json_encode(["success" => 401, "Invalid Request"]);
	} else {
		$getEventQuery = "SELECT * FROM `events` WHERE `event_id` = '$_GET[id]';";
		$getEventDetail = mysqli_query($connection, $getEventQuery);
		if (!$getEventDetail) {
			die("Couldn't fetch event details" . mysqli_error($connection));
		} else {
			$eventDetail = json_encode($row = mysqli_fetch_assoc($getEventDetail));
			$res = ["status" => 200, "data" => $eventDetail];
			echo json_encode($res);
		}
	}
}

if ($_GET["get"] == "toggleGoing") {
	if (!$_GET["id"]) {
		echo json_encode(["success" => 401, "Invalid Request"]);
	} else {
		$selectInviteQuery = "SELECT * FROM  `invitees` WHERE `event_invitee_id` = '$_GET[id]';";
		$selectInvite = mysqli_query($connection, $selectInviteQuery);
		if (!$selectInvite) {
			die("Error:" . mysqli_error($connection));
		} else {
			$selectInvite = mysqli_fetch_assoc($selectInvite);
		}
		if ($selectInvite['user_id'] == $currentUserID) {
			$getInviteQuery = "UPDATE `invitees` SET `user_going` = $_GET[going] WHERE `event_invitee_id` = '$_GET[id]';";
			$getInvite = mysqli_query($connection, $getInviteQuery);
			if (!$getInvite) {
				die("Couldn't complete toggle going action" . mysqli_error($connection));
			} else {
				$res = ["status" => 200, "data" => "Success"];
				echo json_encode($res);
			}
		}
	}
}
if ($_GET["get"] == "deleteEvent") {
	if ($_GET["id"]) {
		$selectEventQuery = "SELECT `event_organizer` from `events` WHERE `event_id` = '$_GET[id]';";
		$selectEvent = mysqli_fetch_assoc(mysqli_query($connection, $selectEventQuery));
		if ($selectEvent["event_organizer"] === $currentUserID) {
			$currentEventQuery = "DELETE from `events` WHERE `event_id` = '$_GET[id]';";
			$currentEvent = mysqli_query($connection, $currentEventQuery);
			if (!$currentEvent) {
				die("ERROR: " . mysqli_error($connection));
			} else {
				echo json_encode(["status" => 200, "data" => "Deleted"]);
			}
		}
	} else {
		echo json_encode(["status" => 400]);
		exit();
	}
}
