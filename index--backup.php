<?php require "database/data.php"; ?>
<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>
<?php
if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
}
?>
<section class="user-list-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
			</div>

		</div>
		<div class="row py-sm">
			<div class="col-lg-9">
				<?php include "user-list.php"; ?>

			</div>
			<div class="col-lg-3">
				<?php include "admin-panel.php"; ?>
			</div>
		</div>
	</div>
</section>

<?php include "partials/footer.php"; ?>