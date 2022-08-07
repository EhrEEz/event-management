// const likeURL = "like.php";
$(".like-button").on("click", function () {
	const likeButton = $(this);
	const friendID = $(this).attr("data-user-id");
	const currentUserID = $(this).attr("data-current-user-id");
	$(this).toggleClass("active");
	console.log(friendID);
	$.ajax({
		type: "POST",
		url: "database/like.php",
		data: {
			mode: "connect_request",
			user_id: currentUserID,
			friend_user_id: friendID,
		},
		success: function (response) {
			response = JSON.parse(response);
			if (response.liked == 1) {
				likeButton.addClass("active");
				likeButton.find(".imp").text("Unfollow ");
			} else if (response.liked == 0) {
				likeButton.removeClass("active");
				likeButton.find(".imp").text("Follow ");
			}
		},
	});
});
