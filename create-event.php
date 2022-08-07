<?php require "database/data.php"; ?>
<?php require "database/form-control.php"; ?>
<?php
include("partials/header.php");
$buffer = ob_get_contents();
ob_end_clean();

$buffer = str_replace("%DYN_TITLE%", "New Event", $buffer);
echo $buffer;

?>
<?php include "partials/sidebar.php"; ?>
<?php
if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
}
?>
<?php if (!$currentUserID && !isset($currentUserID)) {
	header("Location: $link404");
	exit();
} ?>
<section class="header-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="header-wrapper">
					<div class="df-image-wrapper">
						<img src="images/background.jpg" />
					</div>
					<div class="header-title-wrapper">
						<h3>Create Event</h3>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
<?php
$invited = [];

$defaults = [
	"eventName" => "",
	"eventStartDate" => "",
	"eventStartTime" => "",
	"eventEndDate" => "",
	"eventEndTime" => "",
	"eventDescription" => "",
	"invited" => $invited,
];

if (isset($_GET["id"])) {
	$currentEventQuery = "SELECT * FROM `events` WHERE `event_id`= '$_GET[id]';";
	$currentEvent = mysqli_query($connection, $currentEventQuery);
	if (!$currentEvent) {
		die("ERROR: " . mysqli_error($connection));
	}
	$currentEventData = mysqli_fetch_assoc($currentEvent);
	$currentEventInvitedListQuery = "SELECT * FROM `invitees` WHERE `event_id`= '$_GET[id]' ;";
	$currentEventInvitedList = mysqli_query($connection, $currentEventInvitedListQuery);
	if (!$currentEventInvitedList) {
		die("ERROR: " . mysqli_error($connection));
	}
	$currentEventInvitedListData = [];
	while ($row = mysqli_fetch_assoc($currentEventInvitedList)) {
		$currentEventInvitedListData[] = $row['user_id'];
	}

	$sdt = new DateTime($currentEventData["event_start_datetime"]);
	$edt = new DateTime($currentEventData["event_end_datetime"]);
	$et = $edt->format('HH:MM');
	$ed = $edt->format('Y-m-d');
	$st = $sdt->format('HH:MM');
	$sd = $sdt->format('Y-m-d');
	$defaults = [
		"eventName" => "$currentEventData[event_name]",
		"eventStartDate" => "$sd",
		"eventStartTime" => "$st",
		"eventEndDate" => "$ed",
		"eventEndTime" => "$et",
		"eventDescription" => "$currentEventData[event_description]",
		"invited" => $currentEventInvitedListData,
	];
}

function validateForm()
{
	$errors = [];
	global
		$currentUserFollowersProfiles, $defaults;
	if (!$_POST["eventName"]) {
		$errors["eventName"] = "Event Name cannot be empty.";
	} else {
		$defaults["eventName"] = sanitizeData($_POST["eventName"]);
		if (strlen($defaults["eventName"]) > 300) {
			$errors["eventName"] = "Event Name can be 300 characters long.";
		}
	}
	if (!$_POST["eventStartDate"]) {
		$errors["eventStartDate"] = "Event Start Date cannot be empty.";
	} else {
		$defaults["eventStartDate"] = sanitizeData($_POST["eventStartDate"]);
		if (!isRealDate($defaults["eventStartDate"])) {
			$errors["eventStartDate"] = "Invalid Date";
		}
	}
	if (!$_POST["eventEndDate"]) {
		$errors["eventEndDate"] = "Event End Date cannot be empty.";
	} else {
		$defaults["eventEndDate"] = sanitizeData($_POST["eventEndDate"]);
		if (!isRealDate($defaults["eventEndDate"])) {
			$errors["eventEndDate"] = "Invalid Date";
		}
	}
	if (!$_POST["eventStartTime"]) {
		$errors["eventStartTime"] = "Event Start Time cannot be empty.";
	} else {
		$defaults["eventStartTime"] = sanitizeData($_POST["eventStartTime"]);
		if (!strtotime($defaults["eventStartTime"])) {
			$errors["eventStartTime"] = "Invalid Time";
		}
	}
	if (!$_POST["eventEndTime"]) {
		$errors["eventEndTime"] = "Event Time cannot be empty.";
	} else {
		$defaults["eventEndTime"] = sanitizeData($_POST["eventEndTime"]);
		if (!strtotime($defaults["eventEndTime"])) {
			$errors["eventEndTime"] = "Invalid Time";
		}
	}
	if (!$_POST["eventDescription"]) {
		$errors["eventDescription"] = "Event Description cannot be empty.";
	} else {
		$defaults["eventDescription"] = sanitizeData($_POST["eventDescription"]);
	}
	if (isset($_POST["invited"])) {
		$defaults["invited"] = $_POST["invited"];
		foreach ($defaults["invited"] as $invitee) {
			if (!array_key_exists($invitee, $currentUserFollowersProfiles)) {
				$errors["invitees"] = "Invalid member selected.";
				break;
			}
		}
	}
	return $errors;
}
function showEventForm($errors = [])
{
	global $currentUser, $defaults;
	$submitLink = htmlspecialchars($_SERVER["PHP_SELF"]);
	$eventNameError = isset($errors["eventName"]) ? ["error", $errors["eventName"]] : ["", ""];
	$eventStartDateError = isset($errors["eventStartDate"])
		? ["error", $errors["eventStartDate"]]
		: ["", ""];
	$eventStartTimeError = isset($errors["eventStartTime"])
		? ["error", $errors["eventStartTime"]]
		: ["", ""];
	$eventEndDateError = isset($errors["eventEndDate"])
		? ["error", $errors["eventEndDate"]]
		: ["", ""];
	$eventEndTimeError = isset($errors["eventEndTime"])
		? ["error", $errors["eventEndTime"]]
		: ["", ""];
	$eventDescriptionError = isset($errors["eventDescription"])
		? ["error", $errors["eventDescription"]]
		: ["", ""];
	$invitedError = isset($errors["invited"])
		? ["error", $errors["invited"]]
		: ["", ""];
	$currentUser['avatar'] = $currentUser['avatar'] ? $currentUser['avatar'] : "default-avatar.png";
	$inviteesBubbles = "";
	$hiddenChecks = "";
	$edit = '';
	if (isset($_GET['id'])) {
		$edit = <<< EDIT
		 <input type="hidden" name="edit" id="edit" value="1"/><input type="hidden" name="currentEvent" id="currentEvent" value="$_GET[id]"/>
EDIT;
		foreach ($defaults["invited"] as $value) {
			$user = getFullUserProfile($value);

			$hiddenChecks .= <<<HIDCHECK
		<input type="checkbox" name="invited[]" value="$value" class="hidden-check" checked />
HIDCHECK;
			$inviteesBubbles .= <<<PART
			<div class="user-bubble" data-user-id="$user[user_id]">
					<span class="rem-tooltip">
						Uninvite <span class="name">
						$user[first_name] $user[last_name]
						 </span>
					</span>
					<div class="user-avatar-wrapper">
						<div class="df-image-wrapper">
							<img src="media/avatar/$user[avatar]" alt="Avatar of user $user[user_name]">
						</div>
					</div>
				</div>
PART;
		}
	}
	$form = <<<__HERE__
	<section class="create-event-section py-ms">
	 <form action="$submitLink" method="post" class="pb-md">
		 <div class="container">
			 <div class="row">
				 <div class="col-lg-9">
					 <div class="organizer-wrapper pb-sm">
						 <h6>Event Hosted By: </h6>
						 <div class="user-box">
							 <div class="user-avatar">
								 <div class="df-image-wrapper">
									 <img src="media/avatar/$currentUser[avatar]" alt="">
								 </div>
							 </div>
							 <div class="user-text-wrapper fl-col">
								 <div class="name-wrapper">
									 <span class="first-name">
										 $currentUser[first_name]
									 </span> <span class="last-name">$currentUser[last_name]
									 </span>
								 </div>
								 <div class="user-name-wrapper">
									 <span class="user_name ">
										 @$currentUser[user_name]
									 </span>
								 </div>
							 </div>
						 </div>
					 </div>
	 
					 <div class="half-form fl-row fl-wrap">
					 <div class="form-control $eventNameError[0]">
						 <div class="input-wrapper w-100">
							 <input type="text" name="eventName" id="eventName" class="input-control" placeholder="Enter Even Name" value="$defaults[eventName]">
							 <label for="eventName">Event Name</label>
							 </div>
							 <span class='error-message'>$eventNameError[1] </span>						
						 </div>
						 <div class="date-time-field fl-row gap-3 w-50">
					 <div class="form-control $eventStartDateError[0] w-50">
							 <div class="input-wrapper w-100">
								 <input type="text" name="eventStartDate" id="eventStartDate" class="input-control flt-pickr-date" placeholder="Enter Event Start Date"  value="$defaults[eventStartDate]">
								 <label for="eventStartDate">Event Start Date</label>
								 </div>
								 <span class='error-message'>$eventStartDateError[1] </span>						
							 </div>
					 <div class="form-control $eventStartTimeError[0] w-30">
							 <div class="input-wrapper w-100">
								 <input type="text" name="eventStartTime" id="eventStartTime" class="input-control flt-pickr-time" placeholder="Enter Event Start Time" value="$defaults[eventStartTime]">
								 <label for="eventStartTime">Event Start Time</label>
								 </div>
								 <span class='error-message'>$eventStartTimeError[1]</span>						
							 </div>
						 </div>
						 <div class="date-time-field fl-row gap-3 w-50">
						 <div class="form-control $eventEndDateError[0] w-50">
								 <div class="input-wrapper w-100">
									 <input type="text" name="eventEndDate" id="eventEndDate" class="input-control flt-pickr-date" placeholder="Enter Event End Date" value="$defaults[eventEndDate]">
									 <label for="eventEndDate">Event End Date</label>
									 </div>
									 <span class='error-message'>$eventEndDateError[1] </span>						
								 </div>
						 <div class="form-control $eventEndTimeError[0] w-30">
								 <div class="input-wrapper w-100">
									 <input type="text" name="eventEndTime" id="eventEndTime" class="input-control flt-pickr-time" placeholder="Enter Event End Time" value="$defaults[eventEndTime]">
									 <label for="eventEndTime">Event End Time</label>
									 </div>
									 <span class='error-message'>$eventEndTimeError[1]</span>						
								 </div>
							 </div>
						<div class="form-control $eventDescriptionError[0] w-100">
						 <div class="input-wrapper">
							 <textarea name="eventDescription" id="eventDescription" class="input-control" rows="5">$defaults[eventDescription]</textarea>
							 <label for="eventDescription">Event Description</label>
							 </div>
							 <span class='error-message'>$eventDescriptionError[1]</span>						
							 </div>
						 <div class="button-wrapper w-100 fl-row jc-end">
							 <button class="btn btn-global btn--primary" type="submit">Done</button>
						 </div>
					 </div>
				 </div>
				 <div class="col-lg-3">
					 <div class="panel--bordered">
						 <div class="panel-title">
							 <h5>Invite</h5>
						 </div>
						 <div class="panel-description muted small">
							 <p>Invite people who follow you to this event. </p>
						 </div>
						 <div class="panel-user-list fl-row fl-wrap gap-2" id="outsidePanelUserList">
						 $inviteesBubbles
						 </div>
						 <div class="form-control $invitedError[0]">
						 	<span class="error-message">$invitedError[1]</span>
						 </div>
						 <div class="link-wrapper py-sm">
							 <a class="select-friends-button" role="button" data-bs-toggle="modal" data-bs-target="#inviteFormModal">
								 Invite Friends <span class="material-icons-round">person_add</span>
							 </a>
						 </div>
					 </div>
				 </div>
			 </div>
		 </div>
		 <input type="hidden" name="checkSubmit" id="checkSubmit" value="1"/>
		 $edit;
		 <div class="piggy-back" id="inviteFormPiggyback">
		 $hiddenChecks
 
		 </div>
	 </form>
	 </section>
__HERE__;
	echo $form;
}

function popModal()
{
	global $currentUserFollowersProfiles, $defaults;
	$selfLink = htmlspecialchars($_SERVER["PHP_SELF"]);
	echo <<< MODAL1
<div class="modal fade" id="inviteFormModal" tabindex="-1" aria-labelledby="inviteFormModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<div class="modal-title" id="inviteFormModalLabel">
				<strong>Select People to invite</strong>
			</div>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
<form action="$selfLink" method="post">
	<div class="alt-search invite-search-wrapper fl-col jc-center mb-xs">
			<div class="search-wrapper">
			<input type="text" name="inviteQuery" id="inviteQuery" class="search-field"
				placeholder="Search for users" autocomplete="off">
			<label class="search-icon-wrapper" for="inviteQuery"><span
					class="material-icons-round">search</span></label>
		</div>
	</div>
	<div class="select-user-list fl-row fl-wrap gap-2 pb-xs" id="modalSelectUserList">
	</div>
	<div class="search-result-wrapper fl-col gap-2 al-stretch" id="modalUserCheckList">
MODAL1;
	if (count($currentUserFollowersProfiles) == 0) {
		echo <<<__EMPTY
<h5 class="text-center muted">Sorry, To invite people, they need to follow you.</h5>
__EMPTY;
	} else {
		foreach ($currentUserFollowersProfiles as $friendID => $friend) {
			$checked = in_array($friendID, $defaults["invited"]) ? "checked" : "";
			echo <<<MODAL2
					<div class="user-check-wrapper" data-query-index="$friend[user_id] $friend[first_name] $friend[last_name] $friend[user_name]">
						<input type="checkbox" name="invited[]" value="$friend[user_id]" class="hidden-check" $checked />
						<div class="user-box">
							<div class="user-avatar">
								<div class="df-image-wrapper">
									<img src="media/avatar/$friend[avatar]" alt="$friend[first_name] $friend[last_name]'s avatar" class="user-avtr-image">
								</div>
							</div>
							<div class="user-text-wrapper fl-row gap-1">
								<div class="name-wrapper">
									<span class="first-name">
										$friend[first_name]
									</span> <span class="last-name">
										$friend[last_name]
									</span>
								</div>
								<div class="user-name-wrapper">
									<span class="user_name ">
										@$friend[user_name]
									</span>
								</div>
							</div>
						</div>
					</div>
MODAL2;
		}
	}
	echo <<<INVITE_USER2
</div>
</form>
INVITE_USER2;
	echo <<<MODAL_FOOT
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-global btn--grey" data-bs-dismiss="modal" id="cancelInvites">Clear</button>
			<a href="#" type="button" class="btn btn-global btn--primary" role="button" id="confirmInvites" data-bs-dismiss="modal">Confirm</a>
		</div>
	</div>
</div>
</div>
MODAL_FOOT;
}
?>




<?php
if (isset($_POST["checkSubmit"])) {
	$errors = validateForm();
	if (!$errors) {
		postEventForm();
		echo "<script>location.replace('index.php');</script>";
	} else {
		showEventForm($errors);
		popModal();
	}
} else {
	showEventForm();
	popModal();
}
function postEventForm()
{
	global $currentUserID,
		$connection, $defaults;
	$eventID = getID("E_");
	$eventPostQuery = "";
	if (isset($_POST['edit'])) {
		$eventPostQuery = "UPDATE `events` set `event_name` = '$_POST[eventName]', `event_description` = '$_POST[eventDescription]', `event_start_datetime` = '$_POST[eventStartDate] $_POST[eventStartTime]', `event_end_datetime` = '$_POST[eventEndDate] $_POST[eventEndTime]' where `event_id`='$_POST[currentEvent]';";
	} else {
		$eventPostQuery = "INSERT INTO `events` (`event_id`, `event_organizer`, `event_name`, `event_description`, `event_start_datetime`, `event_end_datetime`, `created_date`) VALUES ('$eventID', '$currentUserID', '$_POST[eventName]', '$_POST[eventDescription]', '$_POST[eventStartDate] $_POST[eventStartTime]', '$_POST[eventEndDate] $_POST[eventEndTime]', current_timestamp());";
	}
	$eventPost = mysqli_query($connection, $eventPostQuery);
	if (!$eventPost) {
		die("Query Couldnt be carried out;" . mysqli_error($connection));
	} else {
		if (isset($_POST['edit'])) {
			$deleteRest = "DELETE FROM `invitees` WHERE `event_id` = '$_POST[currentEvent]';";
		} else {
			$deleteRest = "DELETE FROM `invitees` WHERE `event_id` = 'eventID';";
		}
		$deleteRestQuery = mysqli_query($connection, $deleteRest);
		if (!$deleteRestQuery) {
			die("Couldn't execute delete Invitee " . mysqli_error($connection));
		}
		if (isset($_POST['invited'])) {
			foreach ($_POST['invited'] as $userID) {
				$inviteeID = getID("N_");
				$postInvitee = "";
				if (isset($_POST["edit"])) {
					$postInvitee = "INSERT INTO `invitees`(`event_invitee_id`, `event_id`, `user_id`,`user_going`) VALUES ('$inviteeID', '$_POST[currentEvent]', '$userID',0)";
				} else {
					$postInvitee = "INSERT INTO `invitees`(`event_invitee_id`, `event_id`, `user_id`,`user_going`) VALUES ('$inviteeID', '$eventID', '$userID',0)";
				}
				$postInviteeQuery = mysqli_query($connection, $postInvitee);
				if (!$postInviteeQuery) {
					die("Couldn't execute insert invitee " . mysqli_error($connection));
				}
			}
		}
	}
}
?>
<?php include "partials/footer.php"; ?>