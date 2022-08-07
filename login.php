
<?php
require "database/connection.php"; ?>

<?php
if (isset($_SESSION["user_id"])) {
	header("Location: index.php");
}
?>
<?php
include("partials/header.php");
$buffer = ob_get_contents();
ob_end_clean();

$buffer = str_replace("%DYN_TITLE%", "Login- Event Management", $buffer);
echo $buffer;
?>
<?php
$defaults = ["user_name" => ""];
?>
<?php
function showForm($error = "")
{
	global $defaults;
	$self = htmlspecialchars($_SERVER['PHP_SELF']);
	echo <<< __LOGIN_FORM__
	<section class="login-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="login-block-wrapper fl-row my-ms">
					<div class="login-img-wrapper w-20">
						<div class="df-image-wrapper">
							<img src="images/login-side.jpg" alt="Photo by Dids: https://www.pexels.com/photo/pink-and-dark-color-abstract-painting-2911527/">
						</div>
					</div>
					<div class="login-form-wrapper w-80 p-ms fl-col jc-center">
						<h5 class="bold-40">Login</h5>
						<p>Please login to your profile</p>
						<span class="error-message">$error</span>
						<form action="$self" method="post" class="login-form fl-col gap-2">
							<div class="form-control">
								<div class="input-wrapper">
									<input type="text" class="input-control" name="user_name" id="user_name" placeholder="Enter Your Username" value="$defaults[user_name]" />
									<label for="user_name">Username</label>
								</div>
								<span class="error-message"></span>
							</div>
							<div class="form-control">
								<div class="input-wrapper">
									<input type="password" class="input-control" name="password" id="password" placeholder="Enter Your Password" />
									<label for="password">Password</label>
								</div>
								<span class="error-message"></span>
							</div>
							<input type="hidden" name="__CHECK__" value="1">
							<div class="button-wrapper py-sm fl-row gap-4">
							<a href="registration.php" class="btn btn-global btn--black">Create Account</a>								<button class="btn btn-global btn--primary w-100">Login</button>

						</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
__LOGIN_FORM__;
}

if (!isset($_POST['__CHECK__'])) {
	showForm();
} else {
	sanitizeLoginData();
	if ($errors = validateForm()) {
		showForm($errors[0]);
	} else {
		$check_user = "SELECT * FROM `users` where `user_name` = '$_POST[user_name]'";
		$query = mysqli_query($connection, $check_user);
		if (!$query) {
			die("Couldn't query..." . mysqli_error($connection));
		} else {
			$user = mysqli_fetch_assoc($query);
			$_SESSION['user_id'] = "$user[user_id]";
			$currentUserID = $user['user_id'];
			header("Location: index.php");
			exit();
		}
	}
}
?>

<?php

function sanitizeLoginData()
{
	global $defaults;
	$_POST['user_name'] = htmlentities($_POST['user_name']);
	$defaults["user_name"] = $_POST['user_name'];
	$_POST['password'] = htmlentities($_POST['password']);
}

function validateForm()
{
	global $connection;
	$username = $_POST["user_name"];
	$password = $_POST["password"];
	$errors = [];
	$check_user = "SELECT * FROM `users` where `user_name` = '$username'";
	$query = mysqli_query($connection, $check_user);
	if (!$query) {
		die("Couldn't query..." . mysqli_error($connection));
		$errors[] = "No user Found";
	} else {
		$user = mysqli_fetch_assoc($query);
		if ($user) {
			if (password_verify($password, $user['password'])) {
				$_SESSION['user'] = $user['user_id'];
			} else {
				$errors[] = "Invalid Credentials.";
			}
		} else {
			$errors[] = "Invalid Credentials.";
		}
	}
	return $errors;
}

?>
<?php include "partials/footer.php"; ?>