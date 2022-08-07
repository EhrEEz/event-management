<?php require "database/data.php"; ?>
<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>
<?php
if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
}
?>
<?php
$roleName = "";
$errors = [];
?>

<?php function submitRole($value)
{
	global $connection;
	$number = uniqid();
	$varray = str_split($number);
	$len = sizeof($varray);
	$id = array_slice($varray, $len - 6, $len);
	$id = implode(",", $id);
	$id = str_replace(",", "", $id);
	$id = "R_00" . $id;
	$roleSubmit = "INSERT INTO Roles(`role_id`, `role_name`) VALUES ('$id', '$value')";
	$submit_query = mysqli_query($connection, $roleSubmit);
	if (!$submit_query) {
		die("Couldn't Submit The Data" . mysqli_error($connection));
	}
} ?>
<?php function showForm()
{
	global $roleName, $errors;
	$selfLink = htmlspecialchars($_SERVER['PHP_SELF']);
	$nameError = isset($errors["role_name"]) ? $errors["role_name"] : false;
	echo "
						<form action='$selfLink' method='post'>
			<div class='form-control " .
		($nameError ? "error" : "") .
		"'>
		<div class='input-wrapper'>
				<input type='text' name='role_name' id='roleName' placeholder='Role Name' value='$roleName'>
				<label for='role_name'>Role Name: </label>
				<input type='hidden' value='form_submit' name='check_submit'>
		</div>
";
	if ($nameError) {
		echo "<span class='error-message'>";
		foreach ($nameError as $error) {
			echo "$error";
			if ($nameError[count($nameError) - 1] != $error) {
				echo ", ";
			}
		}
		echo "</span>";
	}
	echo <<<__ROLEFORMEND
</div>
<div class="button-group">
	<button class="btn btn-global btn--primary">Submit</button>
</div>
</form>
__ROLEFORMEND;
} ?>
<section class="df-form-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h5>Add a Role</h5>
				<?php if (isset($_POST["check_submit"])) {
					$roleName = $_POST["role_name"];
					$search = "SELECT * FROM `roles` where role_name='$roleName'";
					$searchQuery = mysqli_query($connection, $search);
					$flag = false;
					$foundRows = 0;
					if (!$searchQuery) {
						die("Couldn't query " . mysqli_error($connection));
					} else {
						$flag = true;
						$foundRows = mysqli_num_rows($searchQuery);
					}
					if (strlen($roleName) === 0) {
						$errors["role_name"][] = "Cannot Be Empty";
					}
					if (strlen($roleName) > 50) {
						$errors["role_name"][] = "Too Long";
					} elseif (!ctype_alpha(str_replace(" ", "", $roleName))) {
						$errors["role_name"][] = "Needs to be alphabetical";
					} elseif ($flag && $foundRows != 0) {
						$errors["role_name"][] = "$roleName Already Exists";
					} else {
						submitRole($roleName);
						$roleName = "";
					}
					showForm();
				} else {
					showForm();
				} ?>
			</div>
		</div>
	</div>
</section>
<section class="active-role-section py-ms">

	<div class="container">
		<div class="row">
			<div class="col-lg-4">
				<?php require "admin-panel.php"; ?>
			</div>
			<div class="col-lg-8">
				<div class="role-description-wrapper">

				</div>
			</div>
		</div>
	</div>
</section>

<?php if (isset($_GET["action"])) {
	if ($_GET["action"] == "delete") {
		$mode = $_GET["mod"];
		$fieldPrefix = substr($mode, 0, strlen($mode) - 1);
		$id = $_GET["id"];
		$delete = "DELETE FROM `$mode` where `{$fieldPrefix}_id`='$id'";
		$deleteQuery = mysqli_query($connection, $delete);
		if (!$deleteQuery) {
			die("Delete Execution Failed" . mysqli_error($connection));
		} else {
			header("Location: $_SERVER[PHP_SELF]");
			exit();
		}
	}
} ?>
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
				<a href="#" type="button" class="btn btn-global btn--primary" role="button" id="confirmDelete">Yes</a>
			</div>
		</div>
	</div>
</div>


<?php include "partials/footer.php"; ?>