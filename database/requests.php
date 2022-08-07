<?php require "connection.php"; ?>
<?php
if (isset($_GET["action"]) && $_GET["action"] == "roles") {
	$getRole = "SELECT * FROM Roles where `role_id` = '$_GET[id]'";
	$getRoleQuery = mysqli_query($connection, $getRole);
	if (!$getRoleQuery) {
		echo json_encode(["success" => 0, "error" => mysqli_error($connection)]);
	} else {
		$data = mysqli_fetch_assoc($getRoleQuery);
		echo json_encode(["success" => 1, "data" => $data]);
	}
}
if (isset($_GET["action"]) && $_GET["action"] == "permissions") {
	$getPermission = "SELECT * FROM permission_roles where `role_id` = '$_GET[id]'";
	$getPermissionQuery = mysqli_query($connection, $getPermission);
	if (!$getPermissionQuery) {
		echo json_encode(["success" => 0, "error" => mysqli_error($connection)]);
	} else {
		$data_list = [];
		while ($data = mysqli_fetch_assoc($getPermissionQuery)) {
			$data_list[] = $data;
		}
		echo json_encode(["success" => 1, "data" => $data_list]);
	}
}
