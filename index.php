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

$buffer = str_replace("%DYN_TITLE%", "User Management -Home", $buffer);
echo $buffer;
?>
<?php include "partials/sidebar.php"; ?>

<section class="main-section py-ms">
	<form action="#" method="GET">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-5">
					<div class="alt-search fl-col jc-center mb-xs">
						<div class="search-wrapper">
							<input type="text" name="uniSearch" id="uniSearch" class="search-field" placeholder="Search..." autocomplete="off">
							<label class="search-icon-wrapper" for="uniSearch"><span class="material-icons-round">search</span></label>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="filter-part fl-row al-center">
						<ul class="filter-select fl-row gap-2">
							<li class="filter-select-item">
								<button role="button" class="btn filter-select-link active" data-toggle-target="#userSide">Users</button>
							</li>
							<li class="filter-select-item">
								<button role="button" class="btn filter-select-link active" data-toggle-target="#eventSide">Events</button>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2">
					<strong><em>With ❤️️ from @ehreez</em></strong>
				</div>
				<div class="col-lg-12">
					<hr class="divider" />
				</div>
			</div>


		</div>
	</form>
	<div class="container">
		<div class="row">
			<div class="col-lg-6" id="userSide">
				<div class="sort-part fl-row al-center jc-between">
					<h5>Users</h5>
					<ul class="sort-list fl-row gap-2">
						<li class="sort-item">
							<button class="btn sort-button btn-circle-ms btn--grey" data-sort-mode="latest-first"><span class="material-icons-outlined">
									schedule
								</span></button>
						</li>
						<li class="sort-item">
							<button class="btn sort-button btn-circle-ms btn--grey" data-sort-mode="alphabetical"><span class="material-icons-outlined">
									text_rotation_none
								</span></button>
						</li>
					</ul>
				</div>
				<div class="user-list-section">
					<?php include "user-list.php"; ?>
				</div>
			</div>
			<div class="col-lg-6" id="eventSide">
				<div class="sort-part fl-row al-center jc-between">
					<h5>Events</h5>
					<ul class="sort-list fl-row gap-2">
						<li class="sort-item">
							<button class="btn sort-button btn-circle-ms btn--grey" data-sort-mode="latest-first"><span class="material-icons-outlined">
									schedule
								</span></button>
						</li>
						<li class="sort-item">
							<button class="btn sort-button btn-circle-ms btn--grey" data-sort-mode="alphabetical"><span class="material-icons-outlined">
									text_rotation_none
								</span></button>
						</li>
					</ul>
				</div>
				<?php include "event-list.php"; ?>
			</div>
		</div>
	</div>
</section>

<?php include "partials/footer.php"; ?>