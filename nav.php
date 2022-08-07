<?php
$pages = ["create-roles.php" => "Create Role", "index.php" => "User List",]
?>

<header>
	<nav class="nav-main">
		<div class="nav-list-wrapper">
			<ul class="nav-list">
				<?php
				$current = htmlspecialchars($_SERVER['PHP_SELF']);
				$file = explode("/", $current);
				foreach ($pages as $url => $name) {
					echo "<li class='nav-item'><a href='$url' class='nav-link " . ($url == $file[count($file) - 1] ? "active" : "") . "'>$name</a></li>";
				}
				?>
			</ul>
		</div>
	</nav>
</header>