<?php require "database/data.php"; ?>
<?php
include("partials/header.php");
$buffer = ob_get_contents();
ob_end_clean();


?>
<?php include "partials/sidebar.php"; ?>
<?php
if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
}
?>
<section class="main-section">
	<?php
	if (isset($_GET["action"])) {
		if (($_GET["action"] == "view")) {
			if (isset($_GET["id"])) {
				$currentEventQuery = "SELECT * FROM `events` WHERE `event_id`= '$_GET[id]';";
				$currentEvent = mysqli_query($connection, $currentEventQuery);
				if (!$currentEvent) {
					die("ERROR: " . mysqli_error($connection));
				} else {
					$currentEventData = mysqli_fetch_assoc($currentEvent);
					$buffer = str_replace("%DYN_TITLE%", "$currentEventData[event_name]", $buffer);
					$organizerDetail = getFullUserProfile($currentEventData['event_organizer']);
					$goingPeople = "SELECT `user_id` from `invitees` where `event_id`='$currentEventData[event_id]' and `user_going`=1;";
					$goingPeopleQuery = mysqli_query($connection, $goingPeople);
					$goingPeopleList = [];
					if (!$goingPeopleQuery) {
						die("Error:" . mysqli_error($connection));
					} else {
						while ($row = mysqli_fetch_assoc($goingPeopleQuery)) {
							$goingPeopleList[] = getFullUserProfile($row["user_id"]);
						}
					}
					// $goingToThisEventList = ;
					echo $buffer;
					if ($currentEventData['event_id']) {
	?>
						<div class="container">
							<div class="row">
								<div class="col-lg-12">
									<header>
										<div class="event-detail-cover">
											<div class="df-image-wrapper">
												<img src="images/ev-background.jpg" alt="Event Details">
											</div>
											<div class="event-information fl-row jc-between al-center">
												<div class="lt-wrapper fl-col jc-center">
													<h1><?= $currentEventData['event_name'] ?></h1>
													<div class="event-timings">
														<small class="muted"><?php echo $currentEventData["event_start_datetime"] ?></small>
														<small class="muted"><?php echo $currentEventData["event_end_datetime"] ?></small>
														<?php if ($currentEventData["event_organizer"] === $currentUserID) {
														?>
															<div class="fl-row al-center gap-3 ms-ms">

																<div class="edit-button-wrapper">
																	<a href="create-event.php?id=<?= $currentEventData['event_id'] ?>" class="btn btn-global btn--outlined-bl">
																		Edit Event <span class="material-icons-round">edit</span>
																	</a>
																</div>
																<div class="del-btn-wrapper">
																	<button class="btn btn-circle-md btn--danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-event-name="<?php echo $currentEventData["event_name"]; ?>" data-event-id="<?php echo $currentEventData["event_id"]; ?>" data-user-id="<?php echo $currentUserID; ?>"><span class="material-icons-outlined" title="Cancel This Event">
																			delete
																		</span></button>
																</div>
															</div>
														<?php }
														?>
													</div>
												</div>

												<div class="organizer-wrapper fl-row al-center gap-3">
													<div class="txt-wrapper">
														<small class="muted"><em>Organized By</em></small>
														<h6 class="my-0"><?= $organizerDetail["first_name"] . " " . $organizerDetail["last_name"] ?></h6>
													</div>
													<div class="df-avatar-wrapper">
														<div class="df-image-wrapper">
															<img src="media/avatar/<?= $organizerDetail["avatar"] ?>" alt="<?= $organizerDetail["first_name"] . " " . $organizerDetail["last_name"] . "'s Avatar" ?>">
														</div>
													</div>
												</div>
											</div>
										</div>

									</header>

								</div>
								<div class="col-lg-8">
									<article>
										<div class="event-dt-description-wrapper">
											<h6>More about this event</h6>
											<p>
												<?= $currentEventData["event_description"] ?>
											</p>
										</div>
									</article>
								</div>
								<div class="col-lg-4">
									<div class="panel">
										<div class="panel-title pb-sm">
											<h6>People Going To This Event</h6>
										</div>
										<?php if (count($goingPeopleList) == 0) {
											echo "0 users";
										} ?>
										<div class="fl-row fl-wrap gap-2 user-bubbles-wrapper" id="outsidePanelUserList">

											<?php foreach ($goingPeopleList as $goingUserID => $goingUser) { ?>
												<a href="user-detail.php?id=<?= $goingUser["user_id"] ?>" title="<?= "$goingUser[first_name] $goingUser[last_name]" ?>">
													<div class="no-toggle-bubble" data-user-id="<?= $goingUser["user_id"] ?>">
														<div class="user-avatar-wrapper">
															<div class="df-image-wrapper">
																<img src="media/avatar/<?= $goingUser["avatar"] ?>" alt="Avatar of user <?= "$goingUser[first_name] $goingUser[last_name]" ?>">
															</div>
														</div>
													</div>
												</a>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>

	<?php
					} else {
						header("Location: 404.php");
						exit();
					}
				}
			} else {
				header("Location: $link404");
				exit();
			}
		} elseif ($_GET["action"] == "delete") {
			if (isset($_GET["id"])) {
				$currentEventQuery = "DELETE from `events` WHERE `event_id`= '$_GET[id]';";
				$currentEvent = mysqli_query($connection, $currentEventQuery);
				if (!$currentEvent) {
					die("ERROR: " . mysqli_error($connection));
				} else {
					header("Location: index.php");
					exit();
				}
			} else {
				header("Location: $link404");
				exit();
			}
		}
	} else {
		header("Location: $link404");
		exit();
	} ?>
</section>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="modal-title" id="deleteModalLabel">Are Your Sure you want to delete
					<strong class="mode_name"></strong>?
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-global btn--grey" data-bs-dismiss="modal">No</button>
				<button class="btn btn-global btn--primary" role="button" id="confirmDelete" data-redirect="true">Yes</button>
			</div>
		</div>
	</div>
</div>
<?php include "partials/footer.php"; ?>