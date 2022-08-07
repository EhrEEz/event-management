<?php
if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
}
?>
<div class="panel aside-panel">
	<h5>Active Roles</h5>
	<div class="role-tags-wrapper fl-row fl-wrap gap-2 py-sm">
		<?php while ($role_row = mysqli_fetch_assoc($roles)) {
			echo <<<__TAGLIST
				<div class="role-tag" data-role-id="$role_row[role_id]"><span class="tag-text">$role_row[role_name]</span></div>
__TAGLIST;
		} ?>
	</div>
</div>