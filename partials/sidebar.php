<div class="sidebar-wrapper">
	<ul class="sidebar-list">
		<?php echo <<<USER__BUBBLE
			<li class="sidebar-list-item">
			<a href="user-detail.php?id=$currentUserID" class="sidebar-list-link" aria-label="create event">
				<img src="media/avatar/$currentUser[avatar]" alt="$currentUser[first_name] $currentUser[last_name]'s Avatar">
			</a>
			<div class="sidebar-list-item-tooltip">
				View Profile
			</div>
		</li>
USER__BUBBLE; ?>
		<li class="sidebar-list-item">
			<a href="index.php" class="sidebar-list-link" aria-label="create event">
				<span class="material-icons-round">
					home
				</span>
			</a>
			<div class="sidebar-list-item-tooltip">
				Home
			</div>
		</li>
		<li class="sidebar-list-item">
			<a href="create-event.php" class="sidebar-list-link" aria-label="create event">
				<span class="material-icons-round">
					edit_calendar </span>
			</a>
			<div class="sidebar-list-item-tooltip">
				Create Event
			</div>
		</li>
		<li class="sidebar-list-item">
			<a href="search.php" class="sidebar-list-link" aria-label="create event">
				<span class="material-icons-outlined">
					search
				</span>
			</a>
			<div class="sidebar-list-item-tooltip">
				Search
			</div>
		</li>
		<ul class="sidebar-list" id="bottomList">
			<li class="sidebar-list-item">
				<a href="#" class="sidebar-list-link" aria-label="settings">
					<span class="material-icons-round">
						tune
					</span>
				</a>
				<div class="sidebar-list-item-tooltip">
					Settings
				</div>
				<div class="sidebar-menu">
					<a href="<?php echo "$_SERVER[PHP_SELF]?actionLogout=logout" ?>">Logout</a>
				</div>
			</li>
		</ul>
	</ul>

	<ul class="sidebar-list">
		<li class="sidebar-list-item">
			<button class="sidebar-list-link" id="sidebarInvitation">
				<span class="material-icons-round">
					event
				</span>
			</button>
			<div class="sidebar-list-item-tooltip">
				Invites
			</div>
			<div class="sidebar-menu invitation-menu" aria-hidden="true">
				<div class="sidebar-menu-title">
					<h5>Invitations</h5>
				</div>
				<div class="empty-list-wrapper">
					<div class="ln1 py-xs">
						<span class="empty-list-text">You have no invitations currently.</span>
					</div>

				</div>
				<ul class="sidebar-menu-list invitation-list">

				</ul>
			</div>
		</li>
		<li class="sidebar-list-item">
			<button class="sidebar-list-link">
				<span class="material-icons-outlined">
					visibility
				</span>
			</button>
			<div class="sidebar-list-item-tooltip">
				Following
			</div>
			<div class="sidebar-menu invitation-menu" aria-hidden="true">
				<div class="sidebar-menu-title pb-xs">
					<h5>You're Following</h5>
				</div>
				<div class="empty-list-wrapper">
					<div class="ln1 py-xs">
						<span class="empty-list-text">You have no followers currently.</span>
					</div>
				</div>
				<ul class="sidebar-menu-list follower-list">
					<?php
					foreach ($currentUserFollowingProfiles as $followingID => $following) {
						$active = in_array($followingID, $currentUserFollowingList) ? "active" : "";
					?>
						<li class="user-line">
							<div class="fl-row gap-3">
								<div class="profile-avtr">
									<div class="df-image-wrapper">
										<img src="media/avatar/<?php echo $following['avatar'] ? $following['avatar'] : "default-avatar.png"  ?>" alt="<?php echo "$following[first_name]'s Avatar"; ?>">
									</div>
								</div>
								<div class="profile-desc">
									<div class="ln1 profile-title">
										<a href="user-detail.php?id=<?php echo "$followingID"; ?>" class="profile-title"><?php echo "$following[first_name] $following[last_name]" ?></a>
									</div>
									<div class="ln2 muted-text">
										<a href="user-detail.php?id=<?php echo "$followingID"; ?>">
											@<?php echo "$following[user_name]"; ?>
										</a>
									</div>
								</div>
							</div>
						</li>

					<?php
					}
					?>
				</ul>
			</div>
		</li>
		<li class="sidebar-list-item">
			<button class="sidebar-list-link">
				<span class="material-icons-outlined">
					favorite
				</span>
			</button>
			<div class="sidebar-list-item-tooltip">
				Followers
			</div>
			<div class="sidebar-menu invitation-menu" aria-hidden="true">
				<div class="sidebar-menu-title">
					<h5>Your Followers</h5>
				</div>
				<div class="empty-list-wrapper">
					<div class="ln1 py-xs">
						<span class="empty-list-text">You have no followers currently.</span>
					</div>
				</div>
				<ul class="sidebar-menu-list follower-list">
					<?php
					foreach ($currentUserFollowersProfiles as $followerID => $follower) {
						$active = in_array($followerID, $currentUserFollowingList) ? "active" : "";
					?>
						<li class="user-line">
							<div class="fl-row jc-between">
								<div class="profile-avtr">
									<div class="df-image-wrapper">
										<img src="media/avatar/<?php echo $follower['avatar'] ?>" alt="<?php echo "$follower[first_name]'s Avatar"; ?>">
									</div>
								</div>
								<div class="profile-desc">
									<div class="ln1 profile-title">
										<a href="user-detail.php?id=<?php echo "$followerID"; ?>" class="profile-title"><?php echo "$follower[first_name] $follower[last_name]" ?></a>
									</div>
									<div class="ln2 muted-text">
										<a href="user-detail.php?id=<?php echo "$followerID"; ?>">
											@<?php echo "$follower[user_name]"; ?>
										</a>
									</div>
								</div>
								<div class=" profile-direct-link">
									<button class="btn btn-global btn-circle-md btn--grey like-button <?php echo $active; ?>" data-current-user-id="<?php echo $currentUserID; ?>" data-user-id="<?= $followerID ?>"><span class="custom-icon">
											like
										</span></button>
								</div>
							</div>
						</li>

					<?php
					}
					?>
				</ul>
			</div>
		</li>
		<li class="sidebar-list-item">
			<button class="sidebar-list-link" id="userEventsButton">
				<span class="material-icons-outlined">
					perm_contact_calendar
				</span>
			</button>
			<div class="sidebar-list-item-tooltip">
				Your Events
			</div>
			<div class="sidebar-menu invitation-menu" aria-hidden="true">
				<div class="sidebar-menu-title">
					<h5>Your Events</h5>
				</div>
				<div class="empty-list-wrapper">
					<div class="ln1 py-xs">
						<span class="empty-list-text">You have no events currently.</span>
					</div>
					<div class="ln2 py-xs">
						<span class="direct-link">
							<a href="create-event.php" class="btn btn-global btn--primary">Create Event</a>
						</span>
					</div>
				</div>
				<ul class="sidebar-menu-list event-list">

				</ul>
			</div>
		</li>

	</ul>

</div>