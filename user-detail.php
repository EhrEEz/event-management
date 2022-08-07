<?php require "database/data.php"; ?>
<?php
if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
}
?>
<?php
include("partials/header.php");
$buffer = ob_get_contents();
ob_end_clean();

?>
<?php include "partials/sidebar.php"; ?>

<?php
if (!$_GET["id"]) {
	echo json_encode(["success" => 401, "Invalid Request"]);
} else {
	$userFollowingList = getFollowingUserProfiles($_GET["id"]);
}
?>
<?php
$checkDB = "SELECT `avatar`, `cover_picture` FROM `profiles` where `user_id`='$currentUserID'";
$checkDBQuery = mysqli_query($connection, $checkDB);
$isComplete = false;
if (!$checkDBQuery) {
	die("Error on DB: " . mysqli_error($connection));
	header("Location: $link500");
	exit();
} else {
	$row = mysqli_fetch_assoc($checkDBQuery);
	if ($row['avatar'] && $row['cover_picture']) {
		$isComplete = true;
	}
}
?>
<?php if (isset($_GET["id"])) {
	$detailUserProfile = getFullUserProfile($_GET["id"]);
	$buffer = str_replace("%DYN_TITLE%", $detailUserProfile["first_name"] . " $detailUserProfile[last_name]" . "'s Profile", $buffer);
	echo $buffer;
	if (!$detailUserProfile["user_id"]) {
		header("Location: $link404");
		exit();
	}
} else {
	header("Location: $link404");
	exit();
} ?>
<section class="main-section">
	<article>
		<header>
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12 p-0">
						<div class="banner-wrapper">
							<div class="df-image-wrapper">
								<img src="media/cover-pictures/<?php echo $detailUserProfile['cover_picture']; ?>" alt="<?php echo "$detailUserProfile[first_name] {$detailUserProfile['last_name']}'s Cover Picture" ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="fl-row gap-5">
							<div class="dt-avatar-wrapper">
								<div class="df-image-wrapper">
									<img src="media/avatar/<?php echo $detailUserProfile['avatar'] ?>" alt="<?php echo "$detailUserProfile[first_name] {$detailUserProfile['last_name']}'s Avatar" ?>">
								</div>
							</div>
							<div class="dt-title-wrapper py-sm">
								<div class="title-wrapper fl-row al-center gap-4">
									<h1><?php echo "$detailUserProfile[first_name] $detailUserProfile[last_name]"; ?></h1>
									<div class="dt-user-follow-wrapper fl-row al-center gap-2 py-ms">
										<?php
										if ($_GET['id'] === $currentUserID) {
										?>
											<a href="edit-user.php" class="btn btn-global btn--outlined-bl"><span class="material-icons-round">
													mode_edit
												</span> Edit Profile</a>
											<?php
											if (!$isComplete) {
											?>
												<a class="btn btn-global btn--secondary" href="complete-registration.php">Complete Your Profile</a>
											<?php
											}
										}
										if ($_GET['id'] !== $currentUserID) {
											$active = in_array($detailUserProfile["user_id"], $currentUserFollowingList) ? "active" : "";
											?>
											<button class="btn btn-global btn--grey like-large like-button <?php echo $active ?> " data-user-id="<?php echo $detailUserProfile['user_id'] ?>" data-current-user-id="<?php echo $currentUserID ?>"><span class="custom-icon">
													like
												</span> <span class="button-text"> <span class="imp">
														<?php
														$let = $active == "active" ?  "Unfollow" :  "Follow";
														echo $let;
														?>
													</span><?php echo $detailUserProfile['first_name'] ?></span></button>
										<?php
										}
										?>
									</div>
								</div>
								<h5 class="text-muted">@<?php echo "$detailUserProfile[user_name]"; ?></h5>
							</div>

						</div>
					</div>
				</div>
			</div>
		</header>
		<section style="margin-top: -3rem" class="pb-sm">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-lg-4">
						<div class="user-detail-section">
							<h5>Read about <?php echo $detailUserProfile['first_name'] ?></h5>
							<p>
								<?php echo $detailUserProfile['bio'] ? $detailUserProfile['bio'] : "This wonderful user hasn't updated their bio yet." ?>
							</p>
						</div>
					</div>
					<div class="col-lg-5">
						<div class="dt-detail-wrapper">
							<div class="panel">
								<div class="fl-row al-stretch gap-4">
									<?php if ($detailUserProfile['phone_number']) { ?>
										<div class="dt-info-wrapper">
											<small>Phone Number</small>
											<h6><?php echo $detailUserProfile['phone_number']; ?></h6>
										</div>
									<?php } ?>
									<?php if ($detailUserProfile['email']) { ?>
										<div class="dt-info-wrapper">
											<small>Email Address</small>
											<h6><?php echo $detailUserProfile['email']; ?></h6>
										</div>
									<?php } ?>
									<?php if ($detailUserProfile['user_role'] === "R_0000000f") { ?>
										<div class="dt-info-wrapper fl-row jc-center al-center">
											<div class="role-tag-wrapper">
												<span class="material-icons-round rl-ic">
													admin_panel_settings
												</span>
												Admin
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="panel">
							<div class="panel-title pb-sm">
								<h6>People <?php if ($_GET['id'] === $currentUserID) {
															echo "You Follow";
														} else {
															echo "$detailUserProfile[first_name] Follows";
														} ?></h6>
							</div>
							<div class="fl-row fl-wrap gap-2 user-bubbles-wrapper" id="outsidePanelUserList">
								<?php
								if (!$userFollowingList) {
								?>
									<p><?php echo $detailUserProfile['first_name'] ?> hasn't followed anyone yet.</p>
								<?php
								}
								foreach ($userFollowingList as $friendID => $friendData) {
								?>
									<a href="user-detail.php?id=<?php echo $friendID ?>" title="<?php echo "$friendData[first_name] $friendData[last_name]";  ?>">
										<div class="no-toggle-bubble" data-user-id="<?php echo $friendID ?>">
											<div class="user-avatar-wrapper">
												<div class="df-image-wrapper">
													<img src="media/avatar/<?php echo $friendData['avatar'] ?>" alt="Avatar of user <?php echo "$friendData[first_name] $friendData[last_name]";  ?>">
												</div>
											</div>
										</div>
									</a>
								<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="user-events-list py-sm">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<h4><?php if ($_GET['id'] === $currentUserID) {
									echo "Your";
								} else {
									echo "$detailUserProfile[first_name]'s";
								} ?> Events</h4>
					</div>
					<div class="col-lg-12">
						<div class="event-card-list mb-ms">
							<?php
							$pageUserEvents = getUserEvents($_GET["id"]);
							if (count($pageUserEvents) === 0) {
							?>
								<div class="empty-card event-card">
									<div class="event-title-wrapper">
										<h5>Such Empty.</h5>
									</div>
								</div>
						</div>
						<?php
							} else {
								foreach ($pageUserEvents as $event) {
						?>
							<div class="event-card" data-event-id="<?php echo $event["event_id"]; ?>">
								<a href="event-detail.php?action=view&id=<?php echo $event["event_id"]; ?>">
									<div class="event-title-wrapper">
										<h5><?php echo $event["event_name"]; ?></h5>
									</div>
									<div class="event-sub-wrapper">
										<div class="event-start">
											<span class="start-label">Starts</span>: <span class="start-value">
												<?php echo $event["event_start_datetime"]; ?>
											</span>
										</div>
										<div class="event-end">
											<span class="end-label">Ends</span>: <span class="end-value">
												<?php echo $event["event_end_datetime"]; ?>
											</span>
										</div>
									</div>
								</a>

								<?php if ($_GET["id"] === $currentUserID) {
								?>
									<div class="del-btn-wrapper">
										<button class="btn btn-circle-md btn--danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-event-name="<?php echo $event["event_name"]; ?>" data-event-id="<?php echo $event["event_id"]; ?>" data-user-id="<?php echo $currentUserID; ?>"><span class="material-icons-outlined" title="Cancel This Event">
												delete
											</span></button>
									</div>
							</div>
					<?php }
								} ?>

				<?php
							}
				?>
					</div>
				</div>

			</div>
			</div>
		</section>
	</article>
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
				<button class="btn btn-global btn--primary" role="button" id="confirmDelete">Yes</button>
			</div>
		</div>
	</div>
</div>
<?php include "partials/footer.php"; ?>