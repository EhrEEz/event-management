<div class="main-event-card my-sm">
	<?php
	$defEventList = getEventList();

	foreach ($defEventList as $eventID => $event) {
	?>
		<a href="event-detail.php?action=view&id=<?= $eventID ?>">
			<div class="event-line">
				<div class="line-left">
					<div class="event-title">
						<h6><?php echo $event["event_name"] ?></h6>
					</div>
					<div class="event-timings">
						<small class="muted"><?php echo $event["event_start_datetime"] ?></small>
						<small class="muted"><?php echo $event["event_end_datetime"] ?></small>
					</div>
				</div>
				<div class="ev-line-bg">
				</div>
				<div class="line-right fl-row al-center gap-2">
					<div class="label-wrapper fl-col jc-center">
						<small class="half-bright"><em>Organized By:</em></small>
						<small><?= $event["event_organizer"]["first_name"] . " " . $event["event_organizer"]["last_name"] ?></small>

					</div>
					<div class="ev-avatar-wrapper">
						<img src="media/avatar/<?= $event["event_organizer"]["avatar"] ?>" alt="<?= $event["event_organizer"]["first_name"] . " " . $event["event_organizer"]["last_name"] ?>">
					</div>
				</div>

			</div>
		</a>
	<?php
	}
	?>
</div>