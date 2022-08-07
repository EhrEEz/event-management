const baseURL = "database/requests.php";
$(window).on("load", function () {
	$(".flt-pickr-date").flatpickr({
		minDate: "today",
	});
	$(".flt-pickr-time").flatpickr({
		enableTime: true,
		noCalendar: true,
		dateFormat: "H:i",
		time_24hr: true,
	});
});
function getPanel(role_name, data) {
	let privileges = "";
	for (var v of data["data"]) {
		privileges += `
			<div class="privilege-wrapper">
				<h6>${v["permission_name"]}</h6>
				<p>${v["permission_description"]}</p>
			</div>`;
	}
	if (data["data"].length === 0) {
		privileges += `<h6>No privileges specified</h6>`;
	}

	const panel = `
				<div class="panel">
					<div class="panel-heading w-100 fl-row jc-between al-center">
						<h5>${role_name["data"]["role_name"]} Privileges</h5>
						<button class="btn btn-circle-ms btn--danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-role-name="${role_name["data"]["role_name"]}" data-link="?action=delete&mod=roles&id=${role_name["data"]["role_id"]}"><span class="material-icons-outlined" title="Delete Role ${role_name["data"]["role_name"]}">
						delete
						</span></button>
					</div>
						<div class="privilege-list fl-col gap-2 jc-center py-xs">
							${privileges}
						</div>
					</div>
	`;
	return $(panel);
}

function getUserBubble(data) {
	return `<div class="user-bubble" data-user-id="${data.user_id}">
				<span class="rem-tooltip">
					Uninvite <span class="name">${data.first_name} ${data.last_name} </span>
				</span>
				<div class="user-avatar-wrapper">
					<div class="df-image-wrapper">
						<img
							src="${data.avatar}"
							alt="Avatar of user ${data.user_id}">
					</div>
				</div>
			</div>`;
}
$(document).ready(function () {
	$(".role-tag").on("click", function () {
		const id = $(this).attr("data-role-id");
		$.ajax({
			type: "GET",
			url: baseURL + `?action=roles&id=${id}`,
			data: $(this).serialize,
			success: function (response) {
				var roleData = JSON.parse(response);
				$.ajax({
					type: "GET",
					url: baseURL + `?action=permissions&id=${id}`,
					data: $(this).serialize,
					success: function (response) {
						var permissionData = JSON.parse(response);
						$(".role-description-wrapper").html(getPanel(roleData, permissionData));
					},
				});
			},
		});
	});

	$("#deleteModal").on("show.bs.modal", function (event) {
		const button = $(event.relatedTarget);
		const eventName = button.attr("data-event-name");
		const eventID = button.attr("data-event-id");
		const userID = button.attr("data-user-id");
		$(this).find("#confirmDelete").attr("data-event-id", eventID);
		$(this).find("#confirmDelete").attr("data-user-id", userID);
		$(this).find(".mode_name").text(eventName);
	});
	$(document).on("click", "#confirmDelete", function () {
		const eventID = $(this).attr("data-event-id");
		const userID = $(this).attr("data-user-id");
		const redirect = $(this).attr("data-redirect") === "true" ? true : false;
		$.ajax({
			type: "get",
			url: "database/get.php",
			data: {
				get: "deleteEvent",
				id: eventID,
			},
			success: function (response) {
				const res = JSON.parse(response);
				if (res.status === 200) {
					$("#deleteModal").modal("hide");
					$(`.event-card[data-event-id='${eventID}']`).remove();
					if (redirect) {
						location.replace(`user-detail.php?id=${userID}`);
					}
				}
			},
			error: function (error) {
				console.log(JSON.parse(error));
			},
		});
	});

	$("#inviteQuery").on("keyup", function () {
		const searchResultsWrapper = $(this)
			.parents(".invite-search-wrapper")
			.siblings(".search-result-wrapper");
		const listChildren = searchResultsWrapper.children();
		const searchQuery = $(this).val().toLowerCase();
		listChildren.filter(function () {
			$(this).toggle($(this).attr("data-query-index").toLowerCase().includes(searchQuery));
		});
	});

	$("#inviteFormModal .user-check-wrapper .hidden-check").on("change", function () {
		console.log($(this).find(".last-name").text());
		const data = {
			avatar: $(this).parents(".user-check-wrapper").find(".user-avtr-image").attr("src"),
			first_name: $(this).parents(".user-check-wrapper").find(".first-name").text(),
			last_name: $(this).parents(".user-check-wrapper").find(".last-name").text(),
			user_id: $(this).val(),
		};
		const checkedUserBubble = getUserBubble(data);
		if ($(this).is(":checked")) {
			$("#modalSelectUserList").append($(checkedUserBubble));
			$("#outsidePanelUserList").append($(checkedUserBubble));
		} else {
			$("#modalSelectUserList").find(`.user-bubble[data-user-id='${data.user_id}']`).remove();
			$("#outsidePanelUserList").find(`.user-bubble[data-user-id='${data.user_id}']`).remove();
		}
	});

	$(document).on("click", "#cancelInvites", function () {
		const currentModal = $(this).parents(".modal-dialog");
		currentModal.find(".hidden-check").each(function () {
			$(this).prop("checked", false);
		});
		$("#outsidePanelUserList").html("");
		$("#inviteFormPiggyback").html("");
		setTimeout(function () {
			$("#modalSelectUserList").html("");
		}, 200);
	});
	$(document).on("click", ".user-bubble", function () {
		const currentUserID = $(this).attr("data-user-id");
		$("#outsidePanelUserList").find(`.user-bubble[data-user-id="${currentUserID}"]`).remove();
		$("#modalSelectUserList").find(`.user-bubble[data-user-id="${currentUserID}"]`).remove();
		$("#modalUserCheckList").find(`.hidden-check[value="${currentUserID}"]`).prop("checked", false);
		$("#inviteFormPiggyback").find(`.hidden-check[value="${currentUserID}"]`).remove();
	});
	function setPiggyBack() {
		const hiddenInputWrapper = $(".piggy-back");
		const hiddenCheckedList = $("#modalUserCheckList").find(".hidden-check:checked");
		hiddenInputWrapper.html("");
		hiddenCheckedList.each(function () {
			const checkClone = $(this).clone();
			hiddenInputWrapper.append(checkClone);
		});
	}
	// $(document).on("click", "#confirmInvites", setPiggyBack);
	$(document).on("hidden.bs.modal", "#inviteFormModal", setPiggyBack);
});
let currentlyOpen = "";
let sideMenuOpenFlag = false;
$(document).ready(function () {
	$(".sidebar-list-link").on("click", function () {
		if ($(this).siblings(".sidebar-menu")) {
			const sideMenu = $(this).siblings(".sidebar-menu");
			currentlyOpen = sideMenu;
			sideMenuOpenFlag = true;
			$(".sidebar-menu").removeClass("show");
			sideMenu.addClass("show");
			if (sideMenu.find(".sidebar-menu-list").children().length === 0) {
				sideMenu.find(".empty-list-wrapper").css("display", "block");
			} else {
				sideMenu.find(".empty-list-wrapper").css("display", "none");
			}
		}
	});
	$(document).on("mouseup", ".going-button", function () {
		const inviteID = $(this).attr("data-invite-id");
		const goingButton = $(this);
		if (!$(this).attr("data-going")) {
			$.ajax({
				type: "get",
				url: "database/get.php",
				data: { get: "toggleGoing", id: inviteID, going: 1 },
				success: function (response) {
					console.log(response);
					const res = JSON.parse(response);
					if (res.status == 200) {
						goingButton.attr("data-going", true);
						goingButton.removeClass("red");
						goingButton.parents(".invitation-item").find(".going-indicator").removeClass("red");
						goingButton.parents(".invitation-item").find(".going-indicator").addClass("green");
						goingButton
							.parents(".invitation-item")
							.find(".going-indicator")
							.text("You are going to this event.");
						goingButton.addClass("green");
						goingButton.html(
							$(`<span class="material-icons-round">
					done
				</span>
				`)
						);
					}
				},
			});
		} else {
			$.ajax({
				type: "get",
				url: "database/get.php",
				data: { get: "toggleGoing", id: inviteID, going: 0 },
				success: function (response) {
					const res = JSON.parse(response);
					if (res.status == 200) {
						goingButton.removeAttr("data-going");
						goingButton.removeClass("green");
						goingButton
							.parents(".invitation-item")
							.find(".going-indicator")
							.text("You are not going to this event.");
						goingButton.parents(".invitation-item").find(".going-indicator").removeClass("green");
						goingButton.parents(".invitation-item").find(".going-indicator").addClass("red");
						goingButton.addClass("red");
						goingButton.html(
							$(`<span class="material-icons-round">
					close
					</span>
					`)
						);
					}
				},
			});
		}
	});
	$(document).on("click", function (event) {
		if (sideMenuOpenFlag && !$(event.target).closest(".sidebar-list-item").length) {
			$(".sidebar-menu.show").removeClass("show");
			sideMenuOpenFlag = false;
			currentlyOpen = [];
		}
	});
});

$(document).ready(function () {
	$(".event-card").each(function () {
		const stVal = $(this).find(".start-value").text();
		const stDate = moment(stVal, "YYYY-MM-DD hh:mm:ss");
		if (stDate < moment()) {
			$(this).find(".start-label").text("Started ");
			$(this).find(".start-value").text(stDate.format("Do MMM, YYYY"));
		} else {
			$(this).find(".start-value").text(stDate.format("Do MMM, YYYY - h:mm A"));
		}
		const endVal = $(this).find(".end-value").text();
		const endDate = moment(endVal, "YYYY-MM-DD hh:mm:ss");
		if (endDate < moment()) {
			$(this).find(".end-label").text("Ended ");
			$(this).find(".end-value").text(endDate.format("Do MMM, YYYY"));
		} else {
			$(this).find(".end-value").text(endDate.format("Do MMM, YYYY - h:mm A"));
		}
		console.log(endDate.format("Do MMM, YYYY"));
	});
});

$(document).ready(function () {
	$(".event-timings")
		.find("small")
		.each(function () {
			const dateText = $(this).text();
			const date = moment(dateText).format("Do MMM, YYYY");
			const time = moment(dateText).format("h:mm A");
			$(this).replaceWith(
				`
				<div class="date-time-wrapper muted">
					<small class="date">${date}</small>
					<small class="time">${time}</small>
				</div>
				`
			);
		});
});

$(document).on("change", ".image-upload-wrapper input", function () {
	const upPath = this.files[0];
	$(this)
		.parents(".image-upload-wrapper")
		.find(".df-avtr-placeholder")
		.attr("src", window.URL.createObjectURL(upPath));
});

$(document).ready(function () {
	let allTargets = [];
	$(this)
		.parents(".filter-select")
		.find(".filter-select-link[data-toggle-target]")
		.each(function () {
			if ($(this).attr("data-toggle-target") !== "all") {
				allTargets = [...allTargets, $(this).attr("data-toggle-target")];
			}
		});
	$(".filter-select-link").on("click", function () {
		const target = $(this).attr("data-toggle-target");
		$(this).toggleClass("active");

		if (target === "all") {
			for (let tg of allTargets) {
				$(`${tg}`).toggle("show");
			}
		} else {
			for (let tg of allTargets) {
				$(`${tg}`).toggle("show");
			}
			$(`${target}`).toggle("show");
		}
	});

	if ($(".invitation-list").children().length > 0) {
		$(".invitation-menu").toggleClass("new-notification");
	}
});
