<?php require "database/connection.php"; ?>
<?php require "database/fatalFunctions.php"; ?>
<?php require "database/form-control.php"; ?>
<?php
if (isset($_SESSION["user_id"])) {
	header("Location: index.php");
}
?>
<?php
include("partials/header.php");
$buffer = ob_get_contents();
ob_end_clean();

$buffer = str_replace("%DYN_TITLE%", "Sign Up - Event Management", $buffer);
echo $buffer; ?>
<?php
$defaults = [
	"user_name" => "",
	"first_name" => "",
	"last_name" => "",
	"phone_number" => "",
	"email" => "",
]
?>

<?php
function showForm($errors = [])
{
	global $defaults;
	$usernameError = isset($errors["user_name"]) ? $errors["user_name"] : "";
	$usernameActive = $usernameError ? "error" : "";
	$first_nameError = isset($errors["first_name"]) ? $errors["first_name"] : "";
	$first_nameActive = $first_nameError ? "error" : "";
	$last_nameError = isset($errors["last_name"]) ? $errors["last_name"] : "";
	$last_nameActive = $last_nameError ? "error" : "";
	$phone_numberError = isset($errors["phone_number"]) ? $errors["phone_number"] : "";
	$phone_numberActive = $phone_numberError ? "error" : "";
	$emailError = isset($errors["email"]) ? $errors["email"] : "";
	$emailActive = $emailError ? "error" : "";
	$passwordError = isset($errors["password"]) ? $errors["password"] : "";
	$passwordActive = $passwordError ? "error" : "";
	$password2Error = isset($errors["cPassword"]) ? $errors["cPassword"] : "";
	$password2Active = $password2Error ? "error" : "";
	echo <<< __REGFORM__
	<section class="login-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<div class="login-block-wrapper fl-row my-ms">
					<div class="login-img-wrapper w-20">
						<div class="df-image-wrapper">
							<img src="images/register-side.jpg" alt="Photo by Dids: https://www.pexels.com/photo/pink-and-dark-color-abstract-painting-2911527/">
						</div>
					</div>
					<div class="login-form-wrapper w-80 p-ms fl-col jc-center">
						<h5 class="bold-40">Register</h5>
						<p>Fill Up the form to create a profile.</p>
						<form action="$_SERVER[PHP_SELF]" method="post" class="fl-wrap half-form gap-2" id="registrationForm">
							<div class="form-control $first_nameActive">
								<div class="input-wrapper">
									<input auto-fill="off" auto-complete="off" type="text" class="input-control" name="first_name" id="first_name" placeholder="First Name" value="$defaults[first_name]" />
									<label for="first_name">First Name</label>
								</div>
								<span class="error-message">$first_nameError</span>
							</div>
							<div class="form-control $last_nameActive">
								<div class="input-wrapper">
									<input auto-fill="off" auto-complete="off" type="text" class="input-control" name="last_name" id="last_name" placeholder="Last Name" value="$defaults[last_name]" />
									<label for="last_name">Last Name</label>
								</div>
								<span class="error-message">$last_nameError</span>
							</div>
							<div class="form-control $usernameActive">
								<div class="input-wrapper">
									<input auto-fill="off" auto-complete="off" type="text" class="input-control" name="user_name" id="user_name" placeholder="Username" value="$defaults[user_name]"/>
									<label for="user_name">Username</label>
								</div>
								<span class="error-message">$usernameError</span>
							</div>
							<div class="form-control $phone_numberActive">
								<div class="input-wrapper">
									<input auto-fill="off" auto-complete="off" type="text" class="input-control" name="phone_number" id="phone_number" placeholder="Phone Number" value="$defaults[phone_number]"/>
									<label for="phone_number">Phone Number</label>
								</div>
								<span class="error-message">$phone_numberError</span>
							</div>
							<div class="form-control $emailActive w-100">
								<div class="input-wrapper">
									<input auto-fill="off" auto-complete="off" type="text" class="input-control" name="email" id="email" placeholder="Email Address" value="$defaults[email]"/>
									<label for="email">Email Address</label>
								</div>
								<span class="error-message">$emailError</span>
							</div>
							<div class="form-control $passwordActive">
								<div class="input-wrapper">
									<input auto-fill="off" auto-complete="off" type="password" class="input-control" name="password" id="password" placeholder="Password"/>
									<label for="password">Password</label>
								</div>
								<span class="error-message">$passwordError</span>
							</div>
							<div class="form-control $password2Active">
								<div class="input-wrapper">
									<input auto-fill="off" auto-complete="off" type="password" class="input-control" name="cPassword" id="cPassword" placeholder="Confirm Password" />
									<label for="cPassword">Confirm Password</label>
								</div>
								<span class="error-message">$password2Error</span>
							</div>
							<input auto-fill="off" auto-complete="off" type="hidden" name="__CHECK__" value="1">
							<div class="button-wrapper pt-sm w-100 fl-row gap-4">
							<a href="login.php" class="btn btn-global btn--black w-40">Login</a>								<button class="btn btn-global btn--primary w-100">Register</button>

						</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
__REGFORM__;
}

function setNewValue()
{
	global $defaults;
	$defaults = [
		"user_name" => $_POST['user_name'],
		"first_name" => $_POST['first_name'],
		"last_name" => $_POST['last_name'],
		"phone_number" => $_POST['phone_number'],
		"email" => $_POST['email']
	];
}
$data = [];
if (!isset($_POST['__CHECK__'])) {
	showForm();
} else {
	setNewValue();
	sanitizeDatas();
	if ($errors = validateForm()) {
		showForm($errors);
	} else {
		submitForm();
		header("Location: login.php");
		exit();
	}
}
?>

<?php
function submitForm()
{
	global $data, $connection;
	$id = getID("U_");
	$passwordHash = password_hash($data["password"], PASSWORD_ARGON2I);
	$userQuery = "INSERT INTO `users`(`user_id`, `user_name`, `email`, `phone_number`, `user_role`,  `password`) VALUES ('$id', '$data[user_name]', '$data[email]', '$data[phone_number]', 'R_00b58cc8', '$passwordHash');";
	$userCreate = mysqli_query($connection, $userQuery);
	if (!$userCreate) {
		die("Couldn't Create User: " . mysqli_error($connection));
	} else {
		$profile = "INSERT INTO `profiles` (`user_id`, `first_name`, `last_name`) VALUES ('$id', '$data[first_name]', '$data[last_name]');";
		$profileQuery = mysqli_query($connection, $profile);
		if (!$profileQuery) {
			die("Couldn't Create Profile: " . mysqli_error($connection));
		}
	}
}
function sanitizeDatas()
{
	global $data, $connection;
	$data["user_name"] = $_POST['user_name'] = mysqli_real_escape_string($connection, htmlentities(trim($_POST['user_name'])));
	$data['first_name'] = $_POST['first_name'] = mysqli_real_escape_string($connection, htmlentities(trim($_POST['first_name'])));
	$data['last_name'] = $_POST['last_name'] = mysqli_real_escape_string($connection, htmlentities(trim($_POST['last_name'])));
	$data['phone_number'] = $_POST['phone_number'] = mysqli_real_escape_string($connection, htmlentities(trim($_POST['phone_number'])));
	$data['email'] = $_POST['email'] = mysqli_real_escape_string($connection, htmlentities(trim($_POST['email'])));
	$data['cPassword'] = $_POST['cPassword'] = mysqli_real_escape_string($connection, htmlentities(trim($_POST['cPassword'])));
	$data['password'] = $_POST['password'] = mysqli_real_escape_string($connection, htmlentities(trim($_POST['password'])));
}


function validateForm()
{
	$username = $_POST["user_name"];
	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$phone_number = $_POST["phone_number"];
	$email = $_POST["email"];
	$password = $_POST["password"];
	$password2 = $_POST["cPassword"];
	$errors = [];
	if (!cln($username, 4)) {
		$errors["username"] = "Username must be at least 5 characters long";
	} elseif (!ctype_alnum($username) && !ctype_alpha(str_replace("-", "", str_replace("_", "", $username)))) {
		$errors["username"] = "Username can only be letters, digits and dashes.";
	} elseif (!seu("user_name", $username)) {
		$errors["username"] = "Username already exists.";
	}
	if (!cln($first_name, 1)) {
		$errors["first_name"] = "First Name must be at least 1 character long";
	} elseif (!ctype_alpha($first_name)) {
		$errors["first_name"] = "First Name can only be letters.";
	}
	if (!cln($last_name, 1)) {
		$errors["last_name"] = "Last Name must be at least 1 character long";
	} elseif (!ctype_alpha($last_name)) {
		$errors["last_name"] = "Last Name can only be letters.";
	}
	if (strlen($phone_number) !== 10 || $phone_number !== strval(intval($phone_number))) {
		$errors["phone_number"] = "Phone Number must be digits and  10 digits.";
	} elseif (!seu("phone_number", $phone_number)) {
		$errors["phone_number"] = "Phone Number you provided is already taken.";
	}
	if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors["email"] = "Invalid Email Address";
	} elseif (!seu("email", $email)) {
		$errors["email"] = "Email Address is already taken.";
	}
	if (!$password) {
		$errors["password"] = "Password is required.";
	} elseif (!cln($password, 8)) {
		$errors["password"] = "Password must be at least 8 characters long.";
	}
	if (!$password2) {
		$errors["c_password"] = "Confirm Password";
	}
	return $errors;
}

?>
<?php include "partials/footer.php"; ?>