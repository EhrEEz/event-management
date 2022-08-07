<?php require "database/data.php" ?>
<?php
if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
} else {
	$checkDB = "SELECT `avatar`, `cover_picture` FROM `profiles` where `user_id`='$currentUserID'";
	$checkDBQuery = mysqli_query($connection, $checkDB);
	if (!$checkDBQuery) {
		die("Error on DB: " . mysqli_error($connection));
		header("Location: $link500");
		exit();
	} else {
		$row = mysqli_fetch_assoc($checkDBQuery);
		if ($row['avatar'] && $row['cover_picture']) {
			header("Location: $link404");
			exit();
		}
	}
}
?>
<?php
include("partials/header.php");
$buffer = ob_get_contents();
ob_end_clean();

$buffer = str_replace("%DYN_TITLE%", "Complete Registration", $buffer);
echo $buffer;
?>
<?php include "partials/sidebar.php"; ?>

<section class="main-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" enctype="multipart/form-data">
					<div class="images-upload-form-wrapper fl-col">
						<h5>Upload Images</h5>
						<div class="image-upload-wrapper avatar-upload-wrapper">
							<img src="media/avatar/default-avatar.png" alt="Upload new Avatar" class="df-avtr-placeholder">
							<div class="image-input-group">
								<label for="avatarUpload">Upload an Avatar</label>
								<input type="file" name="avatarUpload" id="avatarUpload" accept=".jpeg,.jpg,.gif,.png">
							</div>
						</div>
						<div class="image-upload-wrapper cover-upload-wrapper">
							<img src="media/cover-pictures/defaultCover.jpg" alt="Upload new Cover Picture" class="df-avtr-placeholder">
							<div class="image-input-group">
								<label for="coverUpload">Upload a Cover Picture</label>
								<input type="file" name="coverUpload" id="coverUpload" accept="image*/.jpeg,.jpg,.gif,.png">
							</div>
						</div>
						<input type="hidden" name="checkSubmit" value="1" />
						<div class="button-wrapper w-100">
							<button class="btn btn-global btn--primary w-40 mx-auto">Continue</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<?php
// Check if image file is a actual image or fake image
if (isset($_POST["checkSubmit"])) {
	$av_id = getID("A_");
	$co_id = getID("C_");
	$avatar_dir = "media/avatar/";
	$cover_dir = "media/cover-pictures/";
	$avatar_file = $avatar_dir . basename($av_id . "-" . $_FILES["avatarUpload"]["name"]);
	$cover_file = $cover_dir . basename($co_id . "-" . $_FILES["coverUpload"]["name"]);
	$coverOK = 1;
	$avatarOK = 1;
	$avatarFileType = strtolower(pathinfo($avatar_file, PATHINFO_EXTENSION));
	$coverFileType = strtolower(pathinfo($cover_file, PATHINFO_EXTENSION));
	$avatarCheck = getimagesize($_FILES["avatarUpload"]["tmp_name"]);
	$coverCheck = getimagesize($_FILES["coverUpload"]["tmp_name"]);
	if ($coverCheck !== false) {
		$coverOK = 1;
	} else {
		$coverOK = 0;
	}
	if ($avatarCheck !== false) {
		$avatarOK = 1;
	} else {
		$avatarOK = 0;
	}
	if (file_exists($avatar_file)) {
		$avatarOK = 0;
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
	if (
		$avatarFileType != "jpg" && $avatarFileType != "png" && $avatarFileType != "jpeg" &&
		$avatarFileType != "gif"
	) {
		$avatarOK = 0;
	}

	$avatarUpload = 1;
	$coverUpload = 1;

	if ($coverOK == 0) {
		$coverUpload = 0;
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["coverUpload"]["tmp_name"], $cover_file)) {
			$coverUpload = 1;
		} else {
			$coverUpload = 0;
		}
	}
	if ($avatarOK == 0) {
		$avatarUpload = 0;		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["avatarUpload"]["tmp_name"], $avatar_file)) {
			$avatarUpload = 1;
		} else {
			$avatarUpload = 0;
		}
	}

	if ($avatarUpload && $coverUpload) {
		$avatarValue = $av_id . "-" . $_FILES["avatarUpload"]["name"];
		$coverValue = $co_id . "-" . $_FILES["coverUpload"]["name"];
		$uploadQuery = "UPDATE `profiles` SET `avatar` = '$avatarValue', `cover_picture` = '$coverValue' where `user_id`='$_SESSION[user_id]'; ";
		$upload = mysqli_query($connection, $uploadQuery);
		if (!$upload) {
			die("There was an error uploading the files." . mysqli_error($connection));
			header("Location: $link500");
			exit();
		} else {
			echo "<script>location.replace('user-detail.php?id=$currentUserID')</script>";
			header("Location: user-detail.php?id=$currentUserID");
			exit();
		}
	}
}
?>

<?php include "partials/footer.php" ?>