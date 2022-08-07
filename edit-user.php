<?php require "database/data.php"; ?>
<?php require "database/form-control.php"; ?>

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
$buffer = str_replace("%DYN_TITLE%", "Edit Profile", $buffer);
echo $buffer;
?>

<?php include "partials/sidebar.php" ?>
<section class="main-section py-ms">
	<div class="container">
		<div class="row">
			<div class="col-lg-10">
				<div class="form-edit-title py-ms">
					<h3>Edit Your Info</h2>
				</div>
				<?php
				$currentUserProfile = getFullUserProfile($currentUserID);
				$defaults = $currentUserProfile;
				$data = [];
				function showForm($errors = [])
				{
					global $defaults, $currentUserID;
					$first_nameError = isset($errors["first_name"]) ? $errors["first_name"] : "";
					$first_nameActive = $first_nameError ? "error" : "";
					$last_nameError = isset($errors["last_name"]) ? $errors["last_name"] : "";
					$last_nameActive = $last_nameError ? "error" : "";
					$phone_numberError = isset($errors["phone_number"]) ? $errors["phone_number"] : "";
					$phone_numberActive = $phone_numberError ? "error" : "";
					$emailError = isset($errors["email"]) ? $errors["email"] : "";
					$emailActive = $emailError ? "error" : "";
					$bioError = isset($errors["bio"]) ? $errors["bio"] : "";
					$bioActive = $bioError ? "error" : "";
					echo <<< __REGFORM__
					<form action="$_SERVER[PHP_SELF]" method="post" class="fl-wrap half-form gap-2" id="registrationForm" enctype="multipart/form-data">
						<div class="form-control">
							<div class="image-upload-wrapper avatar-upload-wrapper">
								<img src="media/avatar/$defaults[avatar]" alt="Upload new Avatar" class="df-avtr-placeholder">
								<div class="image-input-group">
									<label for="avatarUpload">Upload an Avatar</label>
									<input type="file" name="avatarUpload" id="avatarUpload" value="media/avatar/$defaults[avatar]" accept=".jpeg,.jpg,.gif,.png">
								</div>
							</div>
						</div>
						<div class="form-control">
							<div class="image-upload-wrapper cover-upload-wrapper">
								<img src="media/cover-pictures/$defaults[cover_picture]" alt="Upload new Cover Picture" class="df-avtr-placeholder">
								<div class="image-input-group">
									<label for="coverUpload">Upload a Cover Picture</label>
									<input type="file" name="coverUpload" id="coverUpload" accept="image*/.jpeg,.jpg,.gif,.png">
								</div>
								</div>
						</div>
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
						<div class="form-control $phone_numberActive">
							<div class="input-wrapper">
								<input auto-fill="off" auto-complete="off" type="text" class="input-control" name="phone_number" id="phone_number" placeholder="Phone Number" value="$defaults[phone_number]"/>
								<label for="phone_number">Phone Number</label>
							</div>
							<span class="error-message">$phone_numberError</span>
						</div>
						<div class="form-control $emailActive">
							<div class="input-wrapper">
								<input auto-fill="off" auto-complete="off" type="text" class="input-control" name="email" id="email" placeholder="Email Address" value="$defaults[email]"/>
								<label for="email">Email Address</label>
							</div>
							<span class="error-message">$emailError</span>
						</div>
						<div class="form-control $bioActive">
							<div class="input-wrapper">
								<textarea auto-fill="off" auto-complete="off" type="text" class="input-control" name="bio" id="bio" rows="5">$defaults[bio]</textarea>
								<label for="bio">Bio</label>
							</div>
							<span class="error-message">$bioError</span>
						</div>
						<input auto-fill="off" auto-complete="off" type="hidden" name="__CHECK__" value="1">
						<div class="button-wrapper pt-sm fl-row gap-4 w-100"><a href="user-detail.php?id=$currentUserID" class="btn btn-global btn--grey w-30">Cancel</a><button class="btn btn-global btn--primary w-30">Save</button>
					</div>
					</form>
__REGFORM__;
				}
				function setNewValue()
				{
					global $defaults;
					$defaults = [
						"first_name" => $_POST['first_name'],
						"last_name" => $_POST['last_name'],
						"phone_number" => $_POST['phone_number'],
						"email" => $_POST['email'],
						"bio" => $_POST['bio'],
					];
				}
				$avUpload = 0;
				$coUpload = 0;
				function validateForm()
				{
					global $currentUserID, $avUpload, $coUpload;
					$first_name = $_POST["first_name"];
					$last_name = $_POST["last_name"];
					$phone_number = $_POST["phone_number"];
					$email = $_POST["email"];
					$avatar = $_FILES["avatarUpload"]["name"] ? $_FILES["avatarUpload"] : "";
					$cover_picture = $_FILES["coverUpload"]["name"] ? $_FILES["coverUpload"] : "";
					$errors = [];
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
					} elseif (!seu("phone_number", $phone_number, $currentUserID)) {
						$errors["phone_number"] = "Phone Number you provided is already taken.";
					}
					if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$errors["email"] = "Invalid Email Address";
					} elseif (!seu("email", $email, $currentUserID)) {
						$errors["email"] = "Email Address is already taken.";
					}
					if ($avatar) {
						$av_id = getID("A_");
						$avatar_dir = "media/avatar/";
						$avatar_file = $avatar_dir . basename($av_id . "-" . $_FILES["avatarUpload"]["name"]);
						$avatarOK = 1;
						$avatarFileType = strtolower(pathinfo($avatar_file, PATHINFO_EXTENSION));
						$avatarCheck = getimagesize($_FILES["avatarUpload"]["tmp_name"]);
						if ($avatarCheck !== false) {
							$avatarOK = 1;
						} else {
							$avatarOK = 0;
						}
						if (file_exists($avatar_file)) {
							$avatarOK = 0;
						}
						if (
							$avatarFileType != "jpg" && $avatarFileType != "png" && $avatarFileType != "jpeg" &&
							$avatarFileType != "gif"
						) {
							$avatarOK = 0;
						}
						if ($avatarOK == 0) {
							$errors["avatar"] = "Please input a valid image.";
						} else {
							$avUpload = 1;
						}
					}
					if ($cover_picture) {
						$co_id = getID("C_");
						$cover_dir = "media/cover-pictures/";
						$cover_file = $cover_dir . basename($co_id . "-" . $_FILES["coverUpload"]["name"]);
						$coverOK = 1;
						$coverFileType = strtolower(pathinfo($cover_file, PATHINFO_EXTENSION));
						$coverCheck = getimagesize($_FILES["coverUpload"]["tmp_name"]);
						if ($coverCheck !== false) {
							$coverOK = 1;
						} else {
							$coverOK = 0;
						}
						if (file_exists($cover_file)) {
							$coverOK = 0;
						}
						if (
							$coverFileType != "jpg" && $coverFileType != "png" && $coverFileType != "jpeg" &&
							$coverFileType != "gif"
						) {
							$coverOK = 0;
						}
						if (!$coverOK) {
							$errors["cover_picture"] = "Please input a valid image.";
						} else {
							$coUpload = 1;
						}
					}
					return $errors;
				}
				function sanitizeDatas()
				{
					global $data;
					$data['first_name'] = $_POST['first_name'] = htmlentities(trim($_POST['first_name']));
					$data['last_name'] = $_POST['last_name'] = htmlentities(trim($_POST['last_name']));
					$data['phone_number'] = $_POST['phone_number'] = htmlentities(trim($_POST['phone_number']));
					$data['email'] = $_POST['email'] = htmlentities(trim($_POST['email']));
					$data['bio'] = $_POST['bio'] = htmlentities(trim($_POST['bio']));
				}
				function submitForm()
				{
					global $data, $connection, $currentUserID, $avUpload, $coUpload;
					$av_id = getID("A_");
					$co_id = getID("C_");
					$avatar_dir = "media/avatar/";
					$cover_dir = "media/cover-pictures/";

					$avatar_file = $avatar_dir . basename($av_id . "-" . $_FILES["avatarUpload"]["name"]);
					$cover_file = $cover_dir . basename($co_id . "-" . $_FILES["coverUpload"]["name"]);

					$av_line = "";
					$co_line = "";
					if ($avUpload) {
						if (move_uploaded_file($_FILES["avatarUpload"]["tmp_name"], $avatar_file)) {
							$avatarValue = $av_id . "-" . $_FILES["avatarUpload"]["name"];
							$av_line = ", `avatar` = '$avatarValue'";
						}
					}
					if ($coUpload) {
						if (move_uploaded_file($_FILES["coverUpload"]["tmp_name"], $cover_file)) {
							$coverValue = $co_id . "-" . $_FILES["coverUpload"]["name"];
							$co_line = ", `cover_picture` = '$coverValue'";
						}
					}
					$userQuery = "UPDATE `users` SET  `email`='$data[email]', `phone_number`='$data[phone_number]' where `user_id`='$currentUserID';";
					$userCreate = mysqli_query($connection, $userQuery);
					if (!$userCreate) {
						die("Couldn't Update User: " . mysqli_error($connection));
					} else {
						$profile = "UPDATE `profiles` SET `first_name`='$data[first_name]', `last_name` ='$data[last_name]', `bio`='$data[bio]' {$av_line}{$co_line} WHERE `user_id`='$currentUserID';";
						$profileQuery = mysqli_query($connection, $profile);
						if (!$profileQuery) {
							die("Couldn't Create Profile: " . mysqli_error($connection));
						}
					}
				}
				if (!isset($_POST['__CHECK__'])) {
					showForm();
				} else {
					setNewValue();
					sanitizeDatas();
					if ($errors = validateForm()) {
						showForm($errors);
					} else {
						submitForm();
						echo "<script>location.replace('user-detail.php?id=$currentUserID')</script>";
						exit();
					}
				}
				?>

			</div>
		</div>
	</div>

</section>
<?php include "partials/footer.php" ?>