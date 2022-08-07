<footer></footer>

</main>

<script src="dist/js/jquery.js"></script>
<script src="dist/js/popper.js"></script>
<script src="dist/js/bootstrap.js"></script>
<script src="dist/js/icons.js"></script>
<script src="dist/js/flatpickr.js"></script>
<script src="dist/js/moment.js"></script>
<script src="dist/js/request.js"></script>
<script src="dist/js/custom.js"></script>
<?php
if (isset($_SESSION["user_id"])) {
?>
	<script>
		function getEventItem(data) {
			return `
		<li class="invitation-item" data-event-id="${data.event_id}">
				<a href="event-detail.php?action=view&id=${data.event_id}">
						<div class="event-information">
							<div class="event_name">${data.event_name}</div>
							<div class="invitation-desc">
								<small class="muted">
								${data.event_start_datetime}
								</small>
							</div>
						</div>
				</a>
					</li>
		`;
		}

		function getInviteItem(element, data) {
			return `
		<li class="invitation-item" data-invite-id="${data.event_id }">
				<a href="event-detail.php?action=view&id=${data.event_id }">
						<div class="event-information">
							<div class="event_name">${data.event_name}</div>
							<div class="invitation-desc">
								<small class="muted">
									${data.event_start_datetime}
								</small>
							</div>
						</div>
				</a>
				<span class="going-indicator ${element[1] == 0? "green" : "red"}">You are ${element[1] == 0? "not going" : "going"} to this event.</span>
						<div class="direct-acceptation-wrapper">
							<button class="btn btn-circle-ms btn--secondary going-button ${element[1] == 0? "green" : "red"}" data-invite-id="${element[0]}" title="${element[1] == 0? "Go To This Event" : "Don't Go To This Event"}"><span class="material-icons-outlined" >
									${element[1] == 0? "done" : "close"}
								</span></button>
						</div>
					</li>
		`;
		}

		function setUserEvents(data) {
			for (let element of data) {
				const item = $(getEventItem(element));
				$(".event-list").append(item);
			}
		}

		function setUserInvites(data) {
			for (let element of data) {
				$.ajax({
					type: "get",
					"url": "database/get.php",
					data: {
						get: "eventDetails",
						id: element.event_id,
					},
					success: function(response) {
						const res = JSON.parse(response);
						const item = $(getInviteItem([element.event_invitee_id, element.user_going], JSON.parse(res.data)));
						$(".invitation-list").append(item);
					}
				})
			}
		}
		$(document).ready(function() {
			$.ajax({
				type: "get",
				"url": "database/get.php",
				data: {
					get: "userEvents",
				},
				success: function(response) {
					const res = JSON.parse(response);
					setUserEvents(res.data);
				}
			})

			$.ajax({
				type: "get",
				"url": "database/get.php",
				data: {
					get: "userInvites",
				},
				success: function(response) {
					const res = JSON.parse(response);
					setUserInvites(res.data);
				}
			})
		})
	</script>
<?php } ?>
</body>

</html>