<?php
if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
}
?>
<?php $user_card_list = getUserList();
?>

<section class="user-list-section py-sm">
	<div class="user-list-wrapper fl-row gap-4 fl-wrap jc-center">
		<?php function getUserCard($user, $data)
		{
			global $currentUserFollowingList, $currentUserID;
			$active = in_array($user["user_id"], $currentUserFollowingList) ? "active" : "";
			return <<<__USERCARD__
				<div class="card user-card" data-user-id="$user[user_id]">
					<div class="card-images-wrapper">
						<div class="card-cover">
							<div class="df-image-wrapper">
								<img
									src="media/cover-pictures/$data[cover_picture]"
									alt="$user[user_name]\'s Cover Photo" loading="lazy">
							</div>
						</div>
						<div class="card-avatar">
							<div class="df-image-wrapper">
								<img
									src="media/avatar/$data[avatar]"
									alt="$user[user_name]\'s Avatar">
							</div>
						</div>
					</div>
					<div class="card-text-wrapper">
						<div class="card-title">
							<h6><span class="name-wrapper">
									<a href="user-detail.php?id=$user[user_id]">$data[first_name] $data[last_name]</a>
								</span>
							</h6>
							<span class="muted regular-12">@$user[user_name]</span>
						</div>
						<div class="card-body">
							<div class="regular-14">
								<p>
									$data[bio]
								</p>
							</div>
						</div>
						<div class="card-footer">
							<button class="btn btn-global btn-circle-md btn--grey like-button $active" data-user-id="$user[user_id]" data-current-user-id="$currentUserID"><span
									class="custom-icon">
									like
								</span></button>
						</div>
					</div>
				</div>
__USERCARD__;
		} ?>
		<?php foreach ($user_card_list as $userId => $data) {
			echo getUserCard(
				["user_id" => $userId, "user_name" => $data["user_name"], "user_role" => $data["user_role"]],
				$data["profile"]
			);
		} ?>
	</div>
</section>