<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!file_exists(URL_STORAGE . "configured"))
		header("Location: /admin/init.php");

	if (!verify_login(USER_STUDENT))
		header("Location: /user/logout.php");
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="icon" href="/include/img/navicon.png">
		<title>Helinium Technasium Portfolio's</title>

		<link rel="stylesheet" type="text/css" href="/include/css/main.css">
		<link rel="stylesheet" type="text/css" href="/include/css/index.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/js/sidebar.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<header class="noselect">
				<div id="logo" class="header-item">
					<a
						onclick="set_sidebar(0)"
						href="/user/home.php"
						target="content">
						<img
							src="/include/img/logo.png"
							title="Helinium Technasium"
							alt="Helinium Technasium"
							style="vertical-align: middle;"
							height="20">
					</a>
				</div>
				<div id="logout" class="header-item">
					<a href="/user/logout.php">Uitloggen</a>
				</div>
				<div id="username" class="nopointer">
					<?php echo($_COOKIE["firstname"] . " " .
							$_COOKIE["lastname"]) ?>
				</div>
			</header>

			<div class="sidebar noselect">
				<ol class="sidebar-list">
					<?php
						if ($_COOKIE["gid"] == USER_STUDENT) {
							$years = get_years();
							for ($i = 0; $i < count($years); $i++) {
								echo("
									<li>
										<label
											class='sidebar-item-label'
											for='y" . $i . "'>"
											. $years[$i] . "
										</label>
										<input
											type='checkbox'
											id='y" . $i . "'/>
										<ol class='sidebar-item0'>
								");
							}
							/*$projectspath = URL_STORAGE . PRJ_DIR;
							$projects = scandir($projectspath);

							for ($i = 2; $i < count($projects); $i++) {
								$projectpath =
									$projectspath . $projects[$i];
								$project = scandir($projectpath);

								for ($j = 2; $j < count($project); $j++) {
									$userpath = $projectpath . "/" .
											$_COOKIE["username"] . "/";

									if (file_exists($userpath)) {
										echo("<li><label
												class='sidebar-item-label'"
												. "for='p" . $i . "'>" .
												$projects[$i] . "</label>
												<input type='checkbox'
												id='p" . $i . "'/><ol
												class='sidebar-item0'>");

										for ($k = 0; $k < 3; $k++) {
											echo("
												<li id='" . $i . $j . $k .
												"'><a onclick=\"set_sidebar(
												$(this).closest('li')
												.attr('id'))\" href='
												editor.php?path=" .
												$userpath . PRJ_FILES[$k]
												. "' target='content'>" .
												PRJ_NAMES[$k] . "</a></li>
											");
										}
									}
								}
							}*/
						} else {
							echo("<li><a href='/teacher/users.php'
									target='content'>Gebruikersbeheer
									</a></li>");
							echo("<li><a href='/teacher/projects.php'
									target='content'>Projectbeheer
									</a></li>");
   							if ($_COOKIE["gid"] == USER_ADMIN) {
								//echo("<li><a href='file:////" . getcwd() .
								//		"../" . URL_STORAGE . PRJ_DIR . "
								//		target='content'>Bestandsbeheer (NYI)
								//		</a></li>");
								echo("<li><a href='/admin/motd.php'
										target='content'>Welkomstbericht
										</a></li>");
							}
						}
					?>
				</ol>
			</div>

			<iframe
				id="content"
				name="content"
				src="/user/home.php"
				frameborder="0">
			</iframe>

			<footer class="nopointer noselect">
				<?php
					include($_SERVER["DOCUMENT_ROOT"] .
							"/include/php/footer.php");
				?>
			</footer>
		</div>
	</body>
</html>
